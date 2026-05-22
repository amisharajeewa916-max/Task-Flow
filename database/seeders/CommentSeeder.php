<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $tasks = Task::take(5)->get();

        foreach ($tasks as $index => $task) {
            Comment::create([
                'comment_text' => 'Please review this task and update the status when complete.',
                'task_id' => $task->id,
                'user_id' => $users[$index % $users->count()]->id,
            ]);
        }
    }
}
