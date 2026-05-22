<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $projects = Project::with(['team', 'creator'])->latest()->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();
            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->with(['team', 'creator'])
                ->latest()
                ->get();
        }

        return response()->json([
            'data' => ProjectResource::collection($projects),
            'message' => 'Projects retrieved successfully.',
            'status' => 'success',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'required|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error',
            ], 422);
        }

        $project = Project::create([
            'project_name' => $request->project_name,
            'description' => $request->description,
            'start_date' => $request->start_date ?: null,
            'end_date' => $request->end_date ?: null,
            'team_id' => $request->team_id,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => 'Project created successfully.',
            'status' => 'success',
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json([
            'data' => new ProjectResource($project->load(['team', 'creator'])),
            'message' => 'Project retrieved successfully.',
            'status' => 'success',
        ]);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'required|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error',
            ], 422);
        }

        $project->update($validator->validated());

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => 'Project updated successfully.',
            'status' => 'success',
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);
        $project->delete();

        return response()->json([
            'data' => null,
            'message' => 'Project deleted successfully.',
            'status' => 'success',
        ]);
    }
}
