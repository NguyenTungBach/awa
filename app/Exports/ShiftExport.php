<?php

namespace App\Exports;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\Driver;
use App\Models\ResultAI;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Excel;
use Repository\ShiftRepository;

class ShiftExport implements FromView
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'shift.xlsx';

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
    public function __construct($items, $data)
    {
        $calendars = $data['calendars'] ?? [];
        $this->items = $items;
        $this->lunarJps = $calendars ? array_column($calendars, 'week', 'date'): [];
        $this->lunarJpDayInWeek = $calendars ? array_column($calendars, 'rokuyou', 'date'): [];
        $this->startDate = Carbon::parse($data['startDate']);
        $this->endDate = Carbon::parse($data['endDate']);
    }

    public function view(): View
    {
        return view('exports.shift', [
            'items' => $this->items,
            'lunarJps' => $this->lunarJps,
            'lunarJpDayInWeek' => $this->lunarJpDayInWeek,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

}
