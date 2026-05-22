<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskCard extends Component
{
    use AuthorizesRequests;
    public Task $task;

    public function toggleComplete()
    {
        $this->authorize('update', $this->task);

        $newStatus = $this->task->status === 'completed' ? 'pending' : 'completed';
        $this->task->update(['status' => $newStatus]);

        $this->dispatch('taskUpdated');

        session()->flash('message', 'Task status updated to: ' . ucfirst($newStatus));
    }

    public function setStatus(string $status)
    {
        $this->authorize('update', $this->task);

        $allowed = ['pending', 'in_progress', 'completed'];
        if (!in_array($status, $allowed)) {
            return;
        }

        if ($this->task->status === $status) {
            return; // nothing to do
        }

        $this->task->update(['status' => $status]);

        $this->dispatch('taskUpdated');

        $label = match ($status) {
            'completed'  => 'Done',
            'in_progress' => 'In Progress',
            default       => 'Pending',
        };

        session()->flash('message', "Task marked as {$label}.");
    }

    public function deleteTask()
    {
        $this->authorize('delete', $this->task);
        
        $this->task->delete();
        
        $this->dispatch('taskDeleted');
        
        session()->flash('message', 'Task deleted successfully.');
    }

    public function render()
    {
        return view('livewire.task-card');
    }
}
