<?php

namespace App\Imports;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\File;
use App\Models\ResultAI;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithChunkReading;



class ResultAIImportError implements ToCollection,WithChunkReading
{
    protected $configStatusOff = DAY_OFF_CODE;
    protected $configStatusOn = DAY_OFF_CODE;
    protected $files ;
    public function __construct($file)
    {
        $this->files = $file;
    }

    public function collection(Collection $rows)
    {
        unset($rows[0]);
        $message = [];
        foreach ($rows->toArray() as $row){
            foreach ($row as $keyRow => $valueRow ){
                $arrayRows = explode('_',$valueRow);
                $key = (int)$arrayRows[0];
                $arrayKeyConfigError = array_keys(File::$configMessage);
                if (!in_array($key,$arrayKeyConfigError)) continue;
                $mes = File::$configMessage[$key];
                $message[] = $mes;
            }
        }
        $file = File::where('type','ai')->where('status','on')->update([
            'note' => json_encode($message),
        ]);

    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function batchSize(): int
    {
        return 1000;
    }
}
