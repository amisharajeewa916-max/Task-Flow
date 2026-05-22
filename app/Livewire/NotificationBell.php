<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadNotifications = [];
    public $unreadCount = 0;

    protected $listeners = ['notificationReceived' => 'loadNotifications', 'taskCreated' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $this->unreadNotifications = Notification::where('user_id', Auth::id())
                ->where('status', 'unread')
                ->latest()
                ->get();
            $this->unreadCount = $this->unreadNotifications->count();
        } else {
            $this->unreadNotifications = [];
            $this->unreadCount = 0;
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->update(['status' => 'read']);
            $this->loadNotifications();
            $this->dispatch('notificationMarkedRead');
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        $this->loadNotifications();
        $this->dispatch('notificationMarkedRead');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
