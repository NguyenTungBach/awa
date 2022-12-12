<?php

namespace App\Imports;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\File;
use App\Models\ResultAI;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Repository\ShiftRepository;



class ResultAIImport implements ToCollection, WithChunkReading
{
    protected $configStatusOff = ['D-1', 'D-2', 'D-3', 'D-4'];
    protected $configStatusOn = ['D-1', 'D-2', 'D-3', 'D-4'];
    protected $file;
    protected $configCodeResponseAI = [
        [
            'type' => 'D-1',
            'value' => '公休'
        ], [
            'type' => 'D-2',
            'value' => '固定休'
        ], [
            'type' => 'D-3',
            'value' => '希望休'
        ], [
            'type' => 'D-4',
            'value' => '有給休暇'
        ], [
            'type' => 'R',
            'value' => '社内業務'
        ], [
            'type' => 'S-1',
            'value' => '待機'
        ], [
            'type' => 'S-2',
            'value' => '待機時間'
        ],
    ];

    public function __construct($files = '')
    {
        $this->file = $files;
    }

    public function collection(Collection $rows)
    {
        $course = Course::select('course_code','course_name')->get()->toArray();
        unset($rows[0]);
        $listCourseEmpty = [];
        foreach ($rows->toArray() as $row) {
            if ($row['1'] == '99999') {
                if (!in_array($row[2], array_column($this->configCodeResponseAI, 'type'))) {
                    $listCourseEmpty[] =
                        [
                            'date' => $row[0],
                            'course' => $row[2],
                        ];
                }
            } else {
                $model = new ResultAI();
                $model->driver_code = $row[1];
                $model->date = $row[0];
                $model->result_ai = $row[2];
                $model->start_time = $row[3];
                $model->end_time = $row[4];
                $model->break_time = $row[5];
                $model->save();
            }
        }
        if ($listCourseEmpty != 0) {
            $messageCheck = $this->getMessageCheck($listCourseEmpty,$course);
            $file = File::where('type', 'ai')->where('status', 'on')->update([
                'status' => File::FILE_STATUS_CHECK,
                'note' => json_encode($messageCheck),
            ]);
        } else {
            $message = ['AI データが正常に生成されました'];
            $file = File::where('type', 'ai')->where('status', 'on')->update([
                'status' => File::FILE_STATUS_SUCCESS,
                'note' => json_encode($message),
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    private function getMessageCheck($listCourseEmpty,$course)
    {
        $listMessage = [];
        foreach ($listCourseEmpty as $key => $valueCourse){
            $filtered = Arr::where($course, function ($value, $key) use ($valueCourse) {
                return ($value['course_code'] == $valueCourse['course']);
            });

            if (count($filtered) != 0){
                $arrayCourseConvert = array_values($filtered);
                $message = $valueCourse['date'].'の'.$arrayCourseConvert[0]['course_name'].'は組み込めませんでした。';
                $listMessage[] = $message;
            }
        }
        return $listMessage;
    }
}
