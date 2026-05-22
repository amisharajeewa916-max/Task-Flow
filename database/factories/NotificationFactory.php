<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'message' => fake()->sentence(10),
            'status' => fake()->randomElement(['read', 'unread']),
            'user_id' => User::factory(),
            'task_id' => Task::factory(),
        ];
    }
}
