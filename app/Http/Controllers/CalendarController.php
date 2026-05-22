<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $tasks = Task::whereNotNull('deadline')->orderBy('deadline')->get();
        } elseif ($user->isManager()) {
            $teamIds = $user->teams->pluck('id')->toArray();
            $tasks = Task::whereNotNull('deadline')
                ->where(function($q) use ($user, $teamIds) {
                    $q->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id)
                      ->orWhereHas('project', function($pq) use ($teamIds) {
                          $pq->whereIn('team_id', $teamIds);
                      });
                })->orderBy('deadline')->get();
        } else {
            $tasks = Task::whereNotNull('deadline')
                ->where(function($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id);
                })->orderBy('deadline')->get();
        }

        return view('calendar.index', compact('tasks'));
    }
}
