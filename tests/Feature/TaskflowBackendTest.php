<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskflowBackendTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success_redirects_to_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_failure_shows_error_message()
    {
        User::factory()->create([
            'email' => 'testuser2@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'testuser2@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
    }

    public function test_create_task_valid_title_and_save_redirects_to_task_index()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);

        $response = $this->actingAs($user)->post('/tasks', [
            'task_name' => 'Test Task',
            'description' => 'A new task description.',
            'priority' => 'medium',
            'deadline' => now()->addDays(3)->toDateString(),
            'project_id' => $project->id,
            'assigned_to' => $user->id,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['task_name' => 'Test Task']);
    }

    public function test_empty_title_validation_returns_error()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'task_name' => '',
            'priority' => 'medium',
        ]);

        $response->assertSessionHasErrors('task_name');
    }

    public function test_update_task_changes_task_details()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $user->id, 'created_by' => $user->id]);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'task_name' => 'Updated Task Name',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        $response->assertRedirect("/tasks/{$task->id}");
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'task_name' => 'Updated Task Name', 'status' => 'in_progress']);
    }

    public function test_complete_task_status_changes_to_completed()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $user->id, 'created_by' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'status' => 'completed',
        ]);

        $response->assertRedirect("/tasks/{$task->id}");
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function test_user_cannot_view_or_delete_another_users_task()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $owner->id, 'created_by' => $owner->id]);

        $response = $this->actingAs($other)->get("/tasks/{$task->id}");
        $response->assertStatus(403);

        $deleteResponse = $this->actingAs($other)->delete("/tasks/{$task->id}");
        $deleteResponse->assertStatus(403);
    }

    public function test_api_auth_returns_401_without_token()
    {
        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(401);
        $response->assertJson(['status' => 'error']);
    }

    public function test_api_task_create_returns_201_created()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/tasks', [
                'task_name' => 'API Task',
                'priority' => 'low',
            ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.task_name', 'API Task');
    }

    public function test_regular_user_cannot_access_reports_page()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/reports');
        $response->assertStatus(403);
    }
}
