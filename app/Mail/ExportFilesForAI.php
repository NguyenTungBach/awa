<?php

namespace App\Mail;

use Helper\ResponseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ExportFilesForAI extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($option)
    {
        $this->attachs = $option['attachs'] ?? $this->getFilesAttachments();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Files exported for AI")->view('email.files-for-ai')->attachments();
    }

    private function getFilesAttachments()
    {
        // tien hanh day du lieu sag ben AI bang queue
        $files = Storage::disk('storage_input')->allFiles();
        if (count($files) != 6) return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', 'file khong du', null);
        $result = [];
        foreach ($files as $file) {
            $result[] = Storage::disk('storage_input')->path($file);
        }

        return $result;
    }

    private function attachments()
    {
        $result = [];
        if ($this->attachs) {
            foreach ($this->attachs as $pathToFile) {
                $result[] = $this->attach($pathToFile);
            }
        }

        return $result;
    }
}
