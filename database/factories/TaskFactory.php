<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'task_name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'deadline' => ($dt = fake()->optional()->dateTimeBetween('now', '+30 days')) ? $dt->format('Y-m-d') : null,
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'project_id' => null,
            'assigned_to' => $user->id,
            'created_by' => $user->id,
        ];
    }
}
