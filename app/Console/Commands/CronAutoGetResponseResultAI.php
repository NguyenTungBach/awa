<?php

namespace App\Console\Commands;

use App\Imports\ResultAIImport;
use App\Imports\ResultAIImportError;
use App\Models\Driver;
use App\Models\File;
use App\Models\ResultAI;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CronAutoGetResponseResultAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $nameFile = 'workshift_result.csv';
    protected $nameFileError = 'error.csv';

    protected $signature = 'command:cronAutoGetResponseResultAI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command auto get response reusult AI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $file = File::where('type','ai')->where('status','on')->get()->toArray();
            if (count($file)!= 0 ){
                $fileResponseAI = Storage::disk('custom_download_output_local')->exists($this->nameFile);
                $fileResponseAISuccess = Storage::disk('custom_download_output_local')->exists($this->nameFileError);
                // case check success
                if ($fileResponseAI == true){
                    $content = Storage::disk('custom_download_output_local')->get($this->nameFile);
                    $saveFileResult = Storage::disk('storage_output')->put($this->nameFile,$content);
                    $checkFileSave = Storage::disk('storage_output')->exists($this->nameFile);
                    if ($checkFileSave == true){
                        // xoa toan bo du lieu truoc khi import file moi vao$
                        $arrayDate = explode(',',$file[0]['date_time']);
                        $startDate = $arrayDate[0];
                        $endDate = $arrayDate[1];
                        ResultAI::whereDate('date','>=',$startDate)->whereDate('date','<=',$endDate)->delete();
                        $excel = Excel::import(new ResultAIImport($file), storage_path('app/public/output/'.$this->nameFile));
                        // luuw thanh cong thi xoa het tat ca cac file luon. xoa cac file trong input
                        foreach ($file as $valueNameFile){
                            Storage::disk('storage_input')->delete($valueNameFile['file_name']);
                            Storage::disk('custom_folder_input_local')->delete($valueNameFile['file_name']);
                        }
                        $message = ['AI データが正常に生成されました'];
                        //xoa luon ca file dowload ve
                        Storage::disk('storage_output')->delete($this->nameFile);
                        Storage::disk('custom_download_output_local')->delete($this->nameFile);
                    }
                }
                // case check false
                if($fileResponseAISuccess == true){
                    $content = Storage::disk('custom_download_output_local')->get($this->nameFileError);
                    $saveFileResult = Storage::disk('storage_output')->put($this->nameFileError,$content);
                    $checkFileSave = Storage::disk('storage_output')->exists($this->nameFileError);
                    if ($checkFileSave == true){
                        // xoa toan bo du lieu truoc khi import file moi vao$
                        $arrayDate = explode(',',$file[0]['date_time']);
                        $startDate = $arrayDate[0];
                        $endDate = $arrayDate[1];
//                        $excelError = 'abc.csv';
//                        $a = Storage::get( storage_path('app/public/output/'.$this->nameFileError));
//                        dd($a);
                        // lưu mã thông báo lỗi vào trong excel, cần check với bên AI chưa làm
                        $excel = Excel::import(new ResultAIImportError($file), storage_path('app/public/output/'.$this->nameFileError));

//                        $excel = (new ResultAIImportError)->import('users.csv', null, \Maatwebsite\Excel\Excel::CSV);

                        // luuw thanh cong thi xoa het tat ca cac file luon. xoa cac file trong input
                        foreach ($file as $valueNameFile){
                            Storage::disk('storage_input')->delete($valueNameFile['file_name']);
                            Storage::disk('custom_folder_input_local')->delete($valueNameFile['file_name']);
                        }
                        //xoa luon ca file dowload ve
                        Storage::disk('storage_output')->delete($this->nameFileError);
                        Storage::disk('custom_download_output_local')->delete($this->nameFileError);
                        $file = File::where('type','ai')->where('status','on')->update([
                            'status' => File::FILE_STATUS_ERROR
                        ]);
                    }
                }
            }
        }catch (\Exception $e){
            Log::error('cronAutoGetResponseResultAI' . $e);
        }
    }
}
