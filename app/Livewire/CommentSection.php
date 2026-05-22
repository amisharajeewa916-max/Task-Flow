<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CommentSection extends Component
{
    public Task $task;
    public $comment_text = '';

    protected $rules = [
        'comment_text' => 'required|string|min:1|max:1000',
    ];

    public function submitComment()
    {
        $this->validate();

        $comment = Comment::create([
            'comment_text' => $this->comment_text,
            'task_id' => $this->task->id,
            'user_id' => Auth::id(),
        ]);

        // Notify assigned user if another user commented
        if ($this->task->assigned_to && $this->task->assigned_to !== Auth::id()) {
            Notification::create([
                'user_id' => $this->task->assigned_to,
                'task_id' => $this->task->id,
                'message' => Auth::user()->name . ' commented on your task: "' . $this->task->task_name . '"',
                'status' => 'unread',
            ]);
            $this->dispatch('notificationReceived'); // update bells
        }

        // Notify creator if another user commented
        if ($this->task->created_by !== Auth::id() && $this->task->created_by !== $this->task->assigned_to) {
            Notification::create([
                'user_id' => $this->task->created_by,
                'task_id' => $this->task->id,
                'message' => Auth::user()->name . ' commented on your created task: "' . $this->task->task_name . '"',
                'status' => 'unread',
            ]);
            $this->dispatch('notificationReceived'); // update bells
        }

        $this->comment_text = '';
        $this->task->load('comments.user');
        
        session()->flash('comment_message', 'Comment posted successfully.');
    }

    public function render()
    {
        // Refresh comments list
        $comments = $this->task->comments()->with('user')->latest()->get();
        return view('livewire.comment-section', compact('comments'));
    }
}
