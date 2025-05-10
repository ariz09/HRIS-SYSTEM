<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $title;
    protected $type;

    public function __construct($title, $message, $type = 'general')
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Create notification record
        NotificationModel::create([
            'user_id' => $notifiable->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'is_read' => false
        ]);

        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type
        ];
    }
}
