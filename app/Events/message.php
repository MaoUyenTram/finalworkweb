<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $id;
    public $msg;

    /**
     * Create a new event instance.
     *
     * @param  $request
     */
    public function __construct($msg,$request,$id)
    {
        $this->request = $request;
        $this->id =$id;
        $this->msg =$msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {

        return new Channel('gamesession' . $this->id);
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
