<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiTaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Task::query();

        if ($user->isAdmin()) {
            $query = Task::query();
        } elseif ($user->isManager()) {
            $teamIds = $user->teams->pluck('id')->toArray();
            $query->where(function ($q) use ($user, $teamIds) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id)
                    ->orWhereHas('project', function ($pq) use ($teamIds) {
                        $pq->whereIn('team_id', $teamIds);
                    });
            });
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $tasks = $query->with(['assignedUser', 'project', 'creator'])->orderBy('deadline', 'asc')->paginate(10);
        $payload = TaskResource::collection($tasks)->response()->getData(true);

        return response()->json([
            'data' => $payload['data'],
            'message' => 'Tasks retrieved successfully.',
            'status' => 'success',
            'meta' => $payload['meta'],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error',
            ], 422);
        }

        $task = Task::create([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'priority' => $request->priority,
            'deadline' => $request->deadline ?: null,
            'status' => $request->status ?: 'pending',
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'task_id' => $task->id,
                'message' => 'You have been assigned a new task: ' . $task->task_name,
                'status' => 'unread',
            ]);
        }

        return response()->json([
            'data' => new TaskResource($task->load(['assignedUser', 'project', 'creator'])),
            'message' => 'Task created successfully.',
            'status' => 'success',
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        $task->load(['assignedUser', 'project', 'creator', 'comments.user', 'attachments']);

        return response()->json([
            'data' => new TaskResource($task),
            'message' => 'Task retrieved successfully.',
            'status' => 'success',
        ]);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validator = Validator::make($request->all(), [
            'task_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error',
            ], 422);
        }

        $oldAssignedTo = $task->assigned_to;
        $task->update($validator->validated());

        if ($task->assigned_to && $task->assigned_to !== $oldAssignedTo && $task->assigned_to !== Auth::id()) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'task_id' => $task->id,
                'message' => 'You have been assigned a task: ' . $task->task_name,
                'status' => 'unread',
            ]);
        }

        return response()->json([
            'data' => new TaskResource($task->load(['assignedUser', 'project', 'creator'])),
            'message' => 'Task updated successfully.',
            'status' => 'success',
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json([
            'data' => null,
            'message' => 'Task deleted successfully.',
            'status' => 'success',
        ]);
    }

    public function complete(Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $task->update(['status' => 'completed']);

        return response()->json([
            'data' => new TaskResource($task->fresh(['assignedUser', 'project', 'creator'])),
            'message' => 'Task marked as complete.',
            'status' => 'success',
        ]);
    }
}
