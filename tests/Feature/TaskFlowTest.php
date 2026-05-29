<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_success(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_failure(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_create_task_with_valid_title(): void
    {
        $user = User::where('email', 'manager1@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/tasks', [
            'task_name' => 'New Task via Test',
            'description' => 'Create new task record.',
            'priority' => 'medium',
            'deadline' => now()->addDays(5)->toDateString(),
            'project_id' => null,
            'assigned_to' => $user->id,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['task_name' => 'New Task via Test']);
    }

    public function test_create_task_with_empty_title_fails(): void
    {
        $user = User::where('email', 'manager1@example.com')->first();
        $this->actingAs($user);

        $response = $this->from('/tasks/create')->post('/tasks', [
            'task_name' => '',
            'priority' => 'medium',
        ]);

        $response->assertRedirect('/tasks/create');
        $response->assertSessionHasErrors('task_name');
    }

    public function test_update_task_details(): void
    {
        $user = User::where('email', 'manager1@example.com')->first();
        $task = Task::factory()->create([
            'task_name' => 'Original Task',
            'priority' => 'low',
            'status' => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->put(route('tasks.update', $task->id), [
            'task_name' => 'Updated Task',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        $response->assertRedirect(route('tasks.show', $task->id));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'task_name' => 'Updated Task', 'status' => 'in_progress']);
    }

    public function test_complete_task_changes_status(): void
    {
        $user = User::where('email', 'manager1@example.com')->first();
        $task = Task::factory()->create([
            'task_name' => 'Complete Me',
            'status' => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('tasks.update', $task->id), [
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('tasks.show', $task->id));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function test_completing_assigned_task_notifies_creator(): void
    {
        $creator = User::where('email', 'manager1@example.com')->first();
        $assignee = User::where('email', 'user1@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Assigned Task to Complete',
            'status' => 'pending',
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
        ]);

        $this->actingAs($assignee);
        $response = $this->put(route('tasks.update', $task->id), [
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('tasks.show', $task->id));
        $this->assertDatabaseHas('notifications', [
            'user_id' => $creator->id,
            'task_id' => $task->id,
            'message' => "The task 'Assigned Task to Complete' has been completed by {$assignee->name}.",
        ]);
    }

    public function test_user_cannot_view_or_delete_other_users_tasks(): void
    {
        $owner = User::where('email', 'user1@example.com')->first();
        $other = User::where('email', 'user2@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Private Task',
            'status' => 'pending',
            'created_by' => $owner->id,
            'assigned_to' => $owner->id,
        ]);

        $this->actingAs($other);

        $this->get(route('tasks.show', $task->id))->assertStatus(403);
        $this->delete(route('tasks.destroy', $task->id))->assertStatus(403);
    }

    public function test_user_can_delete_their_own_task(): void
    {
        $user = User::where('email', 'user1@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Task To Delete',
            'status' => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_admin_can_delete_any_task(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $user  = User::where('email', 'user1@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Someone Elses Task',
            'status' => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($admin);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_delete_task_flashes_success_message(): void
    {
        $user = User::where('email', 'user1@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Flash Message Task',
            'status' => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertSessionHas('message', 'Task deleted successfully.');
    }

    public function test_delete_nonexistent_task_returns_404(): void
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', 99999));
        $response->assertStatus(404);
    }

    public function test_task_show_page_renders_for_creator(): void
    {
        $user = User::where('email', 'user1@example.com')->first();

        $task = Task::factory()->create([
            'task_name' => 'Visible Task',
            'status'    => 'pending',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('tasks.show', $task->id));
        $response->assertStatus(200);
    }

    public function test_api_authentication_required(): void
    {
        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(401);
    }

    public function test_api_task_create_returns_created(): void
    {
        $user = User::where('email', 'manager1@example.com')->first();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/tasks', [
                'task_name' => 'API Task',
                'description' => 'Created through API tests.',
                'priority' => 'medium',
            ]);

        $response->assertStatus(201);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('tasks', ['task_name' => 'API Task']);
    }

    public function test_regular_user_cannot_access_reports(): void
    {
        $user = User::where('email', 'user1@example.com')->first();
        $this->actingAs($user);

        $this->get('/reports')->assertStatus(403);
    }
}
