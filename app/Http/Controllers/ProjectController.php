<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
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

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $teams = Team::all();
        } else {
            $teams = Team::where('created_by', $user->id)
                ->orWhereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->get();
        }

        return view('projects.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'nullable|exists:teams,id',
            'new_team_name' => 'nullable|string|max:255|required_without:team_id',
        ]);

        if (!empty($validated['team_id'])) {
            $teamId = $validated['team_id'];
        } else {
            $team = Team::create([
                'team_name' => $validated['new_team_name'],
                'created_by' => Auth::id(),
            ]);
            $team->users()->attach(Auth::id());
            $teamId = $team->id;
        }

        Project::create([
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'] ?: null,
            'end_date' => $validated['end_date'] ?: null,
            'team_id' => $teamId,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('projects.index')->with('message', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = Project::with(['tasks.assignedUser', 'team', 'creator'])->findOrFail($id);
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $teams = Team::all();
        } else {
            $teams = Team::where('created_by', $user->id)
                ->orWhereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->get();
        }

        return view('projects.edit', compact('project', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'required|exists:teams,id',
        ]);

        $project->update([
            'project_name' => $request->project_name,
            'description' => $request->description,
            'start_date' => $request->start_date ?: null,
            'end_date' => $request->end_date ?: null,
            'team_id' => $request->team_id,
        ]);

        return redirect()->route('projects.index')->with('message', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('message', 'Project deleted successfully.');
    }
}
