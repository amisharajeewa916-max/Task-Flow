<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'file_name' => fake()->word() . '.pdf',
            'file_path' => 'private/attachments/' . fake()->uuid() . '.pdf',
            'upload_date' => now(),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
