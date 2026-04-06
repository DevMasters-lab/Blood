<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlert extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $icon;
    public $url;

    // Pass data into the notification when you trigger it
    public function __construct($title, $message, $icon = 'fa-bell', $url = '#')
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->url = $url;
    }

    // Tell Laravel to save this in the database
    public function via($notifiable)
    {
        return ['database'];
    }

    // The actual data saved in the database JSON column
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => $this->url,
        ];
    }
}