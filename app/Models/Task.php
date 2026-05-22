<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'description',
        'priority',
        'deadline',
        'status',
        'task_type',
        'project_id',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected static function booted()
    {
        static::updated(function ($task) {
            if ($task->wasChanged('status') && $task->status === 'completed') {
                $user = auth()->user();
                $userName = $user ? $user->name : 'System';

                // 1. Notify the creator of the task (if they didn't complete it themselves)
                if ($task->created_by && $task->created_by !== ($user ? $user->id : null)) {
                    Notification::create([
                        'user_id' => $task->created_by,
                        'task_id' => $task->id,
                        'message' => "The task '{$task->task_name}' has been completed by {$userName}.",
                        'status' => 'unread',
                    ]);
                }

                // 2. Notify the assignee of the task (if they didn't complete it themselves)
                if ($task->assigned_to && $task->assigned_to !== ($user ? $user->id : null) && $task->assigned_to !== $task->created_by) {
                    Notification::create([
                        'user_id' => $task->assigned_to,
                        'task_id' => $task->id,
                        'message' => "The task '{$task->task_name}' has been marked as completed by {$userName}.",
                        'status' => 'unread',
                    ]);
                }
            }
        });
    }

    // Relations
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class)->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                     ->where('deadline', '<', Carbon::today());
    }

    public function scopeAssignedToUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
