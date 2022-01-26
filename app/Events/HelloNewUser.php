<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HelloNewUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $recipient;
    private $sender;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $sender, User $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->sender->id,
            'name' => $this->sender->name,
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'. $this->recipient->id);
    }
    public function broadcastAs()
    {
        return 'user.notify';
    }
}
