<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }

        // Base Query based on role
        if ($user->isAdmin()) {
            $tasksQuery = Task::query();
            $projects = Project::withCount(['tasks', 'tasks as completed_tasks_count' => function($q) {
                $q->where('status', 'completed');
            }])->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();
            $tasksQuery = Task::whereHas('project', function($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })->orWhere('created_by', $user->id);

            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->withCount(['tasks', 'tasks as completed_tasks_count' => function($q) {
                    $q->where('status', 'completed');
                }])->get();
        }

        // Single query replaces 6 separate COUNT queries
        $counts = (clone $tasksQuery)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed'   THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'pending'     THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN priority = 'high'      THEN 1 ELSE 0 END) as high_priority_count,
                SUM(CASE WHEN priority = 'medium'    THEN 1 ELSE 0 END) as medium_priority_count,
                SUM(CASE WHEN priority = 'low'       THEN 1 ELSE 0 END) as low_priority_count
            ")
            ->first();

        $totalTasks      = (int) ($counts->total           ?? 0);
        $completedTasks  = (int) ($counts->completed       ?? 0);
        $inProgressTasks = (int) ($counts->in_progress     ?? 0);
        $pendingTasks    = (int) ($counts->pending         ?? 0);
        $highPriority    = (int) ($counts->high_priority_count   ?? 0);
        $mediumPriority  = (int) ($counts->medium_priority_count ?? 0);
        $lowPriority     = (int) ($counts->low_priority_count    ?? 0);

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('reports.index', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'highPriority',
            'mediumPriority',
            'lowPriority',
            'completionRate',
            'projects'
        ));
    }
}
