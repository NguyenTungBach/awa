<?php

namespace App\Jobs;

use Helper\Common;
use Helper\ResponseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ConectionAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $startDate;
    protected $endDate;


    public function __construct($startDate,$endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // kiem tra file cos ton tai khong..neu ton tai thif day sang ben thu muc AI
        $files = Storage::disk('storage_input')->allFiles();
        if (count($files) != 6) return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', 'file khong du', null);
        // day du lieu sang ben AI
        foreach ($files as $file) {
            $content = \Illuminate\Support\Facades\Storage::disk('storage_input')->get($file);
            $putExcelForAI = Storage::disk('custom_folder_input_local')->put($file, $content);
        }
        // check du lieu ben AI xem da gui thanh cong chua
        $filesAI = Storage::disk('custom_folder_input_local')->allFiles();
        if (count($filesAI) != 6) return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', 'file khong du', null);

        // sau khi day du lieu dang AI Thanh cong thi luu lai thong tin vafo bang file
        foreach ($filesAI as $name) {
            $create = \App\Models\File::create([
                'file_name' => $name,
                'file_code' => null,
                'type' => 'ai',
                'date_time' => $this->startDate . ',' . $this->endDate,
                'path' => null,
                'model' => null,
                'status' => 'on',
                'note' => null
            ]);
        }
        $production = App::environment();
        if ($production == 'local'){
            $command = escapeshellcmd(PATH_LOCAL_AI);
            $output = shell_exec($command);
        }else{
            $urlPath = Common::getPathAI(); // lây đường dẫn file python
            $urlEnvironment = Common::getEnvironmentAI(); // lấy đường dẫn môi trường ảo
            $output = exec($urlEnvironment.$urlPath);
        }
    }
}
