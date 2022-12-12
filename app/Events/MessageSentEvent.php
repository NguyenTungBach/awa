<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Kreait\Firebase\Messaging\CloudMessage;

class MessageSentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $topic;

    public function __construct($message, $topic)
    {
        $this->message = $message;
        $this->topic = $topic;
        $messaging = app('firebase.messaging');
        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => [
                "body" => $message->content,
                "title" => auth()->user()->user_name,
            ],
            'data' => [
                "app_env" => App::environment(),
                "type" => "message",
                "user_code" => auth()->user()->user_code
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => "default",
                        "content-available" => 1
                    ]
                ],
                'headers' => [
                    "apns-priority" => "5"
                ]
            ]
        ]);
        $messaging->send($message);
    }

    public function broadcastOn()
    {
        return ['izumi-app-chat'];
    }

    public function broadcastAs()
    {
        return "message-group-{$this->message['group_id']}";
    }
}
