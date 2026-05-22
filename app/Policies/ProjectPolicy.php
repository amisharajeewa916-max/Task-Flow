<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Must belong to the team of the project
        $team = $project->team;
        if ($team) {
            return $team->created_by === $user->id || $team->users->contains($user->id);
        }

        return $project->created_by === $user->id;
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            $team = $project->team;
            if ($team) {
                return $team->created_by === $user->id || $team->users->contains($user->id);
            }
            return $project->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            return $project->created_by === $user->id;
        }

        return false;
    }
}
