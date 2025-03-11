<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $messageHtml;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->messageHtml = View::make('chat.partials.message', ['message' => $message])->render();
    }

    public function broadcastOn()
    {
        $userIds = [$this->message->from_user_id, $this->message->to_user_id];
        sort($userIds);
        return new PrivateChannel('chat.' . $userIds[0] . '.' . $userIds[1]);
    }
}
