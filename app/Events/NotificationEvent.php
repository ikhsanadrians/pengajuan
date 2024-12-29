<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable,InteractsWithSockets, SerializesModels;
    public $title;
    public $message;

    public function __construct($message, $title)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            'itb-channel'
        ];
    }

    public function broadcastAs()
    {
        return 'itb-event';
    }
}