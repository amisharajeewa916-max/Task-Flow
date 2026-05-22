<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Base Query based on role-based data isolation
        if ($user->isAdmin()) {
            $baseQuery = Task::query();
            $projectsCount = Project::count();
            $teamsCount = Team::count();
            $usersCount = User::count();
        } elseif ($user->isManager()) {
            $teamIds = $user->teams->pluck('id')->toArray();
            $baseQuery = Task::where(function($q) use ($user, $teamIds) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('project', function($pq) use ($teamIds) {
                      $pq->whereIn('team_id', $teamIds);
                  });
            });
            $projectsCount = Project::whereIn('team_id', $teamIds)->orWhere('created_by', $user->id)->count();
            $teamsCount = count($teamIds);
            $usersCount = User::whereHas('teams', function($q) use ($teamIds) {
                $q->whereIn('teams.id', $teamIds);
            })->count();
        } else {
            $baseQuery = Task::where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
            $projectsCount = Project::whereHas('team.users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->count();
            $teamsCount = $user->teams->count();
            $usersCount = 0; // Regular users don't need this count
        }

        // Single query for all status counts (replaces 4 separate COUNT queries)
        $statusCounts = (clone $baseQuery)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status != 'completed' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status != 'completed' AND deadline IS NOT NULL AND deadline < ? THEN 1 ELSE 0 END) as overdue
            ", [Carbon::today()->toDateString()])
            ->first();

        $totalTasks     = (int) ($statusCounts->total ?? 0);
        $completedTasks = (int) ($statusCounts->completed ?? 0);
        $pendingTasks   = (int) ($statusCounts->pending ?? 0);
        $overdueTasks   = (int) ($statusCounts->overdue ?? 0);

        // Latest tasks (single query with eager loading)
        $latestTasks = (clone $baseQuery)->with(['assignedUser', 'project'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'overdueTasks',
            'projectsCount',
            'teamsCount',
            'usersCount',
            'latestTasks'
        ));
    }
}
