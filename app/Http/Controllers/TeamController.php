<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $teams = Team::with(['users', 'creator'])->latest()->get();
        } else {
            $teams = Team::where('created_by', $user->id)
                ->orWhereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->with(['users', 'creator'])
                ->latest()
                ->get();
        }

        $allUsers = User::orderBy('name')->get();

        return view('teams.index', compact('teams', 'allUsers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'team_name' => 'required|string|max:255',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team = Team::create([
            'team_name' => $request->team_name,
            'created_by' => Auth::id(),
        ]);

        // Attach creator to the team automatically
        $team->users()->attach(Auth::id());

        if ($request->members) {
            $team->users()->syncWithoutDetaching($request->members);
        }

        return redirect()->route('teams.index')->with('message', 'Team created successfully.');
    }

    public function addMember(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        
        // Authorization check: Only team creator or Admin can add members
        if ($team->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $team->users()->syncWithoutDetaching([$request->user_id]);

        return redirect()->route('teams.index')->with('message', 'Member added to team successfully.');
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);

        if ($team->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        // Don't remove the creator from their own team
        if ($team->created_by === (int)$userId) {
            return redirect()->route('teams.index')->with('error', 'Cannot remove the team creator.');
        }

        $team->users()->detach($userId);

        return redirect()->route('teams.index')->with('message', 'Member removed from team successfully.');
    }
}
