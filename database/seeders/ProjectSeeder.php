<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $teamA = Team::where('team_name', 'Campus Sprint Team')->first();
        $teamB = Team::where('team_name', 'Project Delivery Team')->first();

        Project::create([
            'project_name' => 'TaskFlow Launch',
            'description' => 'Feature development and launch planning for TaskFlow MVP.',
            'start_date' => now()->subWeeks(3)->toDateString(),
            'end_date' => now()->addWeeks(2)->toDateString(),
            'team_id' => $teamA->id,
            'created_by' => $teamA->created_by,
        ]);

        Project::create([
            'project_name' => 'Research Collaboration',
            'description' => 'Team coordination for university research tasks and document review.',
            'start_date' => now()->subWeeks(1)->toDateString(),
            'end_date' => now()->addWeeks(5)->toDateString(),
            'team_id' => $teamA->id,
            'created_by' => $teamA->created_by,
        ]);

        Project::create([
            'project_name' => 'Client Proposal Pipeline',
            'description' => 'Manage client proposal tasks, internal review, and final submission.',
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addWeeks(4)->toDateString(),
            'team_id' => $teamB->id,
            'created_by' => $teamB->created_by,
        ]);
    }
}
