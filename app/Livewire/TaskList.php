<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskList extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, pending, in_progress, completed
    public $priority = 'all'; // all, low, medium, high
    public $projectId = 'all'; // all or project id
    public $sortBy = 'deadline'; // deadline, priority, created_at
    public $sortOrder = 'asc'; // asc, desc

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'priority' => ['except' => 'all'],
        'projectId' => ['except' => 'all'],
        'sortBy' => ['except' => 'deadline'],
        'sortOrder' => ['except' => 'asc'],
    ];

    protected $listeners = ['taskCreated' => '$refresh', 'taskDeleted' => '$refresh', 'taskUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPriority()
    {
        $this->resetPage();
    }

    public function updatingProjectId()
    {
        $this->resetPage();
    }

    public function toggleSort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
    }

    public function toggleStatus($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $this->authorize('update', $task);
            $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
            $task->update(['status' => $newStatus]);
            $this->dispatch('taskUpdated');
        }
    }

    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $this->authorize('delete', $task);
            $task->delete();
            $this->dispatch('taskDeleted');
            session()->flash('message', 'Task deleted successfully.');
        }
    }

    public function render()
    {
        $user = Auth::user();

        if (!$user) {
            return <<<'HTML'
            <div>Please log in to view tasks.</div>
            HTML;
        }

        // Load projects for the current user, to support project filtering.
        if ($user->isAdmin()) {
            $projects = Project::latest()->get();
        } else {
            $teamIds = $user->teams->pluck('id')->toArray();
            $projects = Project::whereIn('team_id', $teamIds)
                ->orWhere('created_by', $user->id)
                ->latest()
                ->get();
        }

        // Base Query based on privacy check
        if ($user->isAdmin()) {
            $query = Task::query();
        } elseif ($user->isManager()) {
            $teamIds = $user->teams->pluck('id')->toArray();
            $query = Task::where(function($q) use ($user, $teamIds) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('project', function($pq) use ($teamIds) {
                      $pq->whereIn('team_id', $teamIds);
                  });
            });
        } else {
            $query = Task::where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        // Live Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('task_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by Project
        if ($this->projectId !== 'all' && $this->projectId !== null && $this->projectId !== '') {
            $query->where('project_id', $this->projectId);
        }

        // Filter by Status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Filter by Priority
        if ($this->priority !== 'all') {
            $query->where('priority', $this->priority);
        }

        // Sorting
        // Custom sorting for priority string value
        if ($this->sortBy === 'priority') {
            // High is 3, Medium is 2, Low is 1 (descending) or opposite (ascending)
            $orderRaw = $this->sortOrder === 'asc' 
                ? "FIELD(priority, 'low', 'medium', 'high') ASC" 
                : "FIELD(priority, 'low', 'medium', 'high') DESC";
            $query->orderByRaw($orderRaw);
        } else {
            $query->orderBy($this->sortBy, $this->sortOrder);
        }

        $tasks = $query->with(['assignedUser', 'project'])->paginate(10);

        return view('livewire.task-list', compact('tasks', 'projects'));
    }
}
