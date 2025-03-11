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

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $messageHtml;

    public function __construct(Message $message)
    {
        $this->message = $message;
        // Загружаем связанные данные
        $this->message->load(['fromUser', 'toUser']);
        // Рендерим HTML сообщения
        $this->messageHtml = View::make('chat.partials.message', ['message' => $message])->render();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->to_user_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'messageHtml' => $this->messageHtml,
            'sender' => [
                'id' => $this->message->fromUser->id,
                'name' => $this->message->fromUser->name,
                'avatar' => $this->message->fromUser->avatar
            ]
        ];
    }
}
