<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Kreait\Firebase\Messaging\CloudMessage;

class NoticeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $app_env;
    public $isDeleted;

    public function __construct($message, $topic, $isDeleted = false)
    {
        $this->app_env = App::environment();
        $this->message = $message;
        $this->isDeleted = $isDeleted;

        $optionFcm = [
            'topic' => $topic,
            'notification' => [
                "body" => $message->subject,
                "title" => "新規のお知らせがあります。"
            ],
            'data' => [
                "app_env" => $this->app_env,
                "public_date" => $message->public_date,
                "type" => "notice"
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
        ];
        if ($isDeleted) {
            unset($optionFcm['notification']);
            $optionFcm['android'] = ['priority' => 'high'];
        }

        $messaging = app('firebase.messaging');
        $message = CloudMessage::fromArray($optionFcm);
        $messaging->send($message);
    }

    public function broadcastOn()
    {
        return ['izumi_web_app_notice_channel'];
    }

    public function broadcastAs()
    {
        return 'izumi_web_app_notice_event';
    }
}
