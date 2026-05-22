<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $task = Task::first();
        $user = User::where('role', 'user')->first();

        if ($task && $user) {
            Attachment::create([
                'file_name' => 'project_overview.pdf',
                'file_path' => 'private/attachments/project_overview.pdf',
                'upload_date' => now(),
                'task_id' => $task->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
