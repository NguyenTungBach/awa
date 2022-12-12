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

class AIExportDriverCourse implements FromView, Responsable ,WithTitle
{
    use Exportable;


    private $writerType = Excel::XLSX;

    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct($courses,$drivers)
    {
        $this->courses = $courses;
        $this->drivers = $drivers;
    }

    public function view(): View
    {
        $courseCodes = [];
        foreach ($this->courses as $course) {
            $courseCodes[] = $course['course_code'];
        }
        return view('exports.ai_driver-course',[
           'courses' => $courseCodes,
           'drivers' => $this->drivers,
        ]);
    }

    public function title(): string
    {
        return 'シート1';
    }
}
