<?php


namespace Modules\Chat\app\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender_id;
    public $receiver_id;
    public $created_at;

    public function __construct($user, $message, $created_at, $receiver)
    {
        $this->message = $message;
        $this->sender_id = $user->id;
        $this->receiver_id = $receiver->id;
        $this->created_at = $created_at;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chat');
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->content,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
