<?php

namespace App\Events;

use App\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SessionTick implements ShouldBroadcast
{
    const CHANNEL_PREFIX = 'tick.';

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public $sessionId;


    /**
     * Create a new event instance.
     *
     * @param array $data
     * @param $sessionId
     */
    public function __construct($sessionId, array $data)
    {
        $this->data = $data;
        $this->sessionId = $sessionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // TODO: May use PrivateChannel
        return new Channel(self::getChannelName($this->sessionId));
    }

    static function getChannelName($sessionId) {
        return self::CHANNEL_PREFIX . md5($sessionId);
    }
}
