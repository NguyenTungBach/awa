<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\File;
use App\Models\Message;
use App\Events\MessageSentEvent;
use Carbon\Carbon;
class FileMessageSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $attributes;
    protected $file;
    protected $path = "messagefile";
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = file_get_contents($this->attributes['file']);
        $fileName = "tavanyeu1lanvamaimai.png";//md5(Carbon::now()->format('YmdHis')) . $this->file->getClientOriginalName();
        $fileData = File::create([
            'file_path' => "a",//$this->file->storeAs($this->path, $fileName),
            'file_name' => $fileName,
            "file_extension" => "csv", //$file->getClientOriginalExtension(),
            "file_size" => 1024//$file->getSize(),
        ]);
        $message = Message::create([
            [
                'sender_id' => $this->attributes['user_code'],
                'group_id' => $this->attributes['group_id'],
                'content' => null,
                'file_id' => $fileData->id
            ]
        ]);
        $event = event(new MessageSentEvent($message, 'izumi_chat_topic_' . $this->attributes['group_id']));
    }
}
