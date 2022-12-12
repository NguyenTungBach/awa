<?php

namespace App\Exports;

use App\Models\CourseSchedule;
use Helper\Common;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;




class AIExportCourse implements FromView, Responsable,WithTitle,WithColumnFormatting
{
    use Exportable;


    protected $courses;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct($courses)
    {
        $this->courses = $courses;
    }

    public function view(): View
    {
        $convertDataCourse = [];
        foreach ($this->courses  as $course){
            $convertDataCourse[] = [
                'course_code' => $course['course_code'],
                'start_time' => $course['start_time'],
                'end_time' => $course['end_time'],
                'break_time' => $this->convertToTime($course['break_time']),
                'point' => $course['point'],
                'flag' => $course['flag'],
                'pot' => $course['pot'],
                'owner' => $course['owner']
            ];
        }
        return view('exports.ai_course',[
           'courses' => $convertDataCourse
        ]);
    }

    private function convertToTime($time) {
        $arrayTime = explode('.', $time);

        if (count($arrayTime) != 2) {
            dd($arrayTime);
        }
        $minute = $arrayTime[1] * 60 / 100;
        if (!$minute && strpos($arrayTime[0], ':') === false) {
            $minute = "00";
        }
        return "$arrayTime[0]:$minute";
    }

    public function title(): string
    {
        return 'シート1';
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_TIME2,
            'D' => NumberFormat::FORMAT_DATE_TIME2,
            'E' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
