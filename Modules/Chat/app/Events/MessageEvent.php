<?php

namespace Modules\Chat\app\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $sender_id;

    public $receiver_id;

    public $created_at;

    public function __construct($user, $message, $created_at, $receiver)
    {
        $this->message = $message->message; // Assuming $message is an object with a content property
        $this->sender_id = $user->id;
        $this->receiver_id = $receiver->id;
        $this->created_at = $created_at->toDateTimeString();

    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat.'.$this->receiver_id); // Scoped to receiver for privacy
    }

    public function broadcastWith()
    {
        return [
            'chat' => [
                'message' => $this->message,
                'sender_id' => $this->sender_id,
                'receiver_id' => $this->receiver_id,
                'created_at' => $this->created_at,
            ],
        ];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
