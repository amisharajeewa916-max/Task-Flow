<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Attachment;
use App\Models\Notification;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return view('tasks.index');
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $projects = Project::latest()->get();
            $users = User::orderBy('name')->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();
            
            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->latest()
                ->get();

            $users = User::whereHas('teams', function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds);
            })->orWhere('id', $user->id)
              ->distinct()
              ->orderBy('name')
              ->get();
        }

        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'task_type' => $request->task_type ?? 'normal',
            'status' => 'pending',
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

        return redirect()->route('tasks.index')->with('message', 'Task created successfully.');
    }

    public function show($id)
    {
        $task = Task::with(['assignedUser', 'creator', 'project', 'comments.user', 'attachments.user'])->findOrFail($id);
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $projects = Project::latest()->get();
            $users = User::orderBy('name')->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();
            
            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->latest()
                ->get();

            $users = User::whereHas('teams', function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds);
            })->orWhere('id', $user->id)
              ->distinct()
              ->orderBy('name')
              ->get();
        }

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $oldAssignedTo = $task->assigned_to;

        $task->update($request->validated());

        // Notify if assignment changed
        if ($task->assigned_to && $task->assigned_to !== $oldAssignedTo && $task->assigned_to !== Auth::id()) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'task_id' => $task->id,
                'message' => 'You have been assigned a task: ' . $task->task_name,
                'status' => 'unread',
            ]);
        }

        return redirect()->route('tasks.show', $task->id)->with('message', 'Task updated successfully.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('message', 'Task deleted successfully.');
    }

    public function teamTasks()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admins see ALL team tasks (task_type='team' OR linked to any team project)
            $tasks = Task::where(function($q) {
                    $q->where('task_type', 'team')
                      ->orWhereHas('project', fn($pq) => $pq->whereNotNull('team_id'));
                })
                ->with(['assignedUser', 'project', 'creator'])
                ->latest()
                ->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();

            $tasks = Task::where(function($q) use ($user, $teamIds) {
                    // Team tasks created by or assigned to the user
                    $q->where(function($inner) use ($user) {
                        $inner->where('task_type', 'team')
                              ->where(function($u) use ($user) {
                                  $u->where('created_by', $user->id)
                                    ->orWhere('assigned_to', $user->id);
                              });
                    });

                    // OR tasks linked to a project belonging to the user's teams
                    if (!empty($teamIds)) {
                        $q->orWhereHas('project', fn($pq) => $pq->whereIn('team_id', $teamIds));
                    }
                })
                ->with(['assignedUser', 'project', 'creator'])
                ->latest()
                ->get();
        }

        return view('tasks.team', compact('tasks'));
    }

    public function uploadAttachment(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:5120', // 5MB limit
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            
            // Secure private storage: storage/app/private/attachments
            $filePath = $file->store('private/attachments');

            Attachment::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'upload_date' => now(),
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tasks.show', $task->id)->with('message', 'Attachment uploaded successfully.');
        }

        return redirect()->route('tasks.show', $task->id)->with('error', 'Failed to upload attachment.');
    }

    public function downloadAttachment($id)
    {
        $attachment = Attachment::findOrFail($id);
        $task = $attachment->task;

        // Authorization check via TaskPolicy
        $this->authorize('view', $task);

        $filePath = $attachment->file_path;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $attachment->file_name);
        }

        abort(404, 'File not found on server.');
    }
}
