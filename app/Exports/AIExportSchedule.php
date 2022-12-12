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

class AIExportSchedule implements FromView, Responsable , WithTitle
{
    use Exportable;


    protected $schedule;
    protected $startDate;
    protected $endDate;


    public function __construct($schedules, $startDates, $endDates)
    {
        $this->schedule = $schedules;
        $this->startDate = $startDates;
        $this->endDate = $endDates;
    }

    public function view(): View
    {
        return view('exports.ai_schedule', [
            'schedules' => $this->schedule,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
    public function title(): string
    {
        return 'シート1';
    }
}
