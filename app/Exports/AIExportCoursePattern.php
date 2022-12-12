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

class AIExportCoursePattern implements FromView, Responsable,WithTitle
{
    use Exportable;


    protected $coursePattern;
    protected $listCours;


    public function __construct($coursePatterns)
    {
        $items = [];
        if ($coursePatterns) {
            $codes = array_column($coursePatterns, 'course_code');
            foreach ($coursePatterns as $item) {
                $coursePattern = [];
                foreach ($codes as $code) {
                    foreach ($item['course_patterns'] as $course) {
                        if ($course['course_child_code'] == $code) {
                            $coursePattern[] = $course;
                            break;
                        }
                    }
                }
                $item['course_patterns'] = $coursePattern;
                $items[] = $item;
            }
        }

        $this->coursePattern = $items;
    }

    public function view(): View
    {
        return view('exports.ai_coursePattern', [
            'coursePatterns' => $this->coursePattern ,
        ]);
    }
    public function title(): string
    {
        return 'シート1';
    }
}
