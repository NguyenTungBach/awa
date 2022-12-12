<?php

namespace App\Exports;

use App\Models\Calendar;
use App\Models\CourseSchedule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;
use Repository\CourseScheduleRepository;

class CourseScheduleExport implements FromView, Responsable
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'CourseSchedule.xlsx';

    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;

    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct(string $sortById = 'asc', string $sortByGroup = 'asc', $view_date = '')
    {
        $this->sortById = $sortById;
        $this->sortByGroup = $sortByGroup;
        $this->viewDate = $view_date ?? \Carbon\Carbon::now();

    }

    public function view(): View
    {
        $list = CourseScheduleRepository::getList([
            'sorttype_id' => $this->sortById,
            'sorttype_group' => $this->sortByGroup,
            'view_date' => date('Y-m', $this->viewDate)
        ]);
        if (!isset($list['data'])) {
            $list['data'] = [];
        }
        $items = $list['data'];
        $daysInMonth = \Carbon\Carbon::parse(date('Y-m-d' , $this->viewDate))->daysInMonth;
        $dayStartMonth = \Carbon\Carbon::parse(date('Y-m-d' , $this->viewDate))->startOfMonth()->toDateString();

        $dates = [];
        for($m = 0; $m < $daysInMonth; $m ++) {
            $dates[] = date('Y-m-d', strtotime($dayStartMonth . ' + '. $m . ' days'));
        }
        $daysInWeek = Calendar::whereIn('date', $dates)->pluck('week', 'date')->all();

        return view('exports.course-schedule', [
            'items' => $items,
            'dates' => $dates,
            'daysInWeek' => $daysInWeek
        ]);
    }
}
