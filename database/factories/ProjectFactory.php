<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-30 days', '+7 days');
        $endDate = fake()->dateTimeBetween($startDate, '+60 days');

        return [
            'project_name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'team_id' => \App\Models\Team::factory(),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
