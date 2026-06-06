<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class AnnouncementPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'announcement_id' => $this->announcement->announcement_id,
            'title' => $this->announcement->title,
            'message' => str($this->announcement->message)->limit(100)->toString(),
            'priority' => $this->announcement->priority,
            'target_type' => $this->announcement->target_type,
            'icon' => $this->icon(),
            'url' => route('admin.announcements.notification.show', $this->announcement),
        ]);
    }

    private function icon(): string
    {
        return match ($this->announcement->priority) {
            'urgent' => 'fas fa-exclamation-triangle text-danger',
            'high' => 'fas fa-bullhorn text-warning',
            'low' => 'far fa-bell text-secondary',
            default => 'fas fa-bullhorn text-info',
        };
    }
}
