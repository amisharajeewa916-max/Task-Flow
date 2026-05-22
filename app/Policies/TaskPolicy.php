<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Managers can view if they are on the same team as the project
        if ($user->isManager()) {
            if ($task->project) {
                $team = $task->project->team;
                if ($team && ($team->created_by === $user->id || $team->users->contains($user->id))) {
                    return true;
                }
            }
        }

        // Creator or Assignee
        return $task->created_by === $user->id || $task->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can update/edit the task.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            if ($task->project) {
                $team = $task->project->team;
                if ($team && ($team->created_by === $user->id || $team->users->contains($user->id))) {
                    return true;
                }
            }
        }

        // Regular users can only update if assigned to them or created by them
        return $task->assigned_to === $user->id || $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            if ($task->project) {
                $team = $task->project->team;
                if ($team && ($team->created_by === $user->id || $team->users->contains($user->id))) {
                    return true;
                }
            }
        }

        // Users can delete if they created it
        return $task->created_by === $user->id;
    }
}
