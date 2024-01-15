<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteGuestBookEntryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
