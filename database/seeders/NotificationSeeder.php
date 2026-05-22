<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'user1@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();
        $user3 = User::where('email', 'user3@example.com')->first();
        $manager1 = User::where('email', 'manager1@example.com')->first();

        $taskA = Task::first();
        $taskB = Task::skip(1)->first();

        Notification::create([
            'user_id' => $user1->id,
            'task_id' => $taskA->id,
            'message' => 'New task assignment: ' . $taskA->task_name,
            'status' => 'unread',
        ]);

        Notification::create([
            'user_id' => $user2->id,
            'task_id' => $taskB->id,
            'message' => 'Project update available for your assigned task.',
            'status' => 'unread',
        ]);

        Notification::create([
            'user_id' => $user3->id,
            'task_id' => null,
            'message' => 'Your account has been included in a new collaboration team.',
            'status' => 'read',
        ]);

        Notification::create([
            'user_id' => $manager1->id,
            'task_id' => null,
            'message' => 'A new task was created and requires review.',
            'status' => 'unread',
        ]);
    }
}
