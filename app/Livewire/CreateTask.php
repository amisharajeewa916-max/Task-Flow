<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CreateTask extends Component
{
    public $showModal = false;

    public $task_name = '';
    public $description = '';
    public $priority = 'medium';
    public $deadline = '';
    public $task_type = 'normal';
    public $project_id = null;
    public $assigned_to = null;

    protected $rules = [
        'task_name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:low,medium,high',
        'deadline' => 'nullable|date',
        'task_type' => 'required|in:normal,team',
        'project_id' => 'nullable|exists:projects,id',
        'assigned_to' => 'nullable|exists:users,id',
    ];

    protected $listeners = ['openCreateTaskModal' => 'openModal'];

    public function openModal($projectId = null)
    {
        $this->resetInputFields();
        if ($projectId) {
            $this->project_id = $projectId;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    private function resetInputFields()
    {
        $this->task_name = '';
        $this->description = '';
        $this->priority = 'medium';
        $this->deadline = '';
        $this->task_type = 'normal';
        $this->project_id = null;
        $this->assigned_to = null;
        $this->resetErrorBag();
    }

    public function submit()
    {
        $this->validate();

        $task = Task::create([
            'task_name' => $this->task_name,
            'description' => $this->description,
            'priority' => $this->priority,
            'deadline' => $this->deadline ?: null,
            'task_type' => $this->task_type,
            'status' => 'pending',
            'project_id' => $this->project_id ?: null,
            'assigned_to' => $this->assigned_to ?: null,
            'created_by' => Auth::id(),
        ]);

        // Send a notification if assigned to another user
        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'task_id' => $task->id,
                'message' => 'You have been assigned a new task: ' . $task->task_name,
                'status' => 'unread',
            ]);
        }

        $this->closeModal();
        $this->dispatch('taskCreated');
        $this->dispatch('notificationReceived'); // Update bells

        session()->flash('message', 'Task created successfully.');
    }

    public function render()
    {
        $user = Auth::user();
        
        if (!$user) {
            return <<<'HTML'
            <div></div>
            HTML;
        }

        if ($user->isAdmin()) {
            $projects = Project::latest()->get();
            $users = User::orderBy('name')->get();
        } else {
            // Get user's teams
            $teamIds = $user->teams->pluck('id')->toArray();
            
            // Get projects associated with user's teams or created by user
            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->latest()
                ->get();

            // Get team members the user can assign tasks to
            $users = User::whereHas('teams', function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds);
            })->orWhere('id', $user->id)
              ->distinct()
              ->orderBy('name')
              ->get();
        }

        return view('livewire.create-task', compact('projects', 'users'));
    }
}
