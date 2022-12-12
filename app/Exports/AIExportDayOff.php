<?php

namespace App\Exports;

use App\Models\CourseSchedule;
use Carbon\Carbon;
use Helper\Common;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Excel;

class AIExportDayOff implements FromView, Responsable,WithTitle
{
    use Exportable;

    public function __construct($dayOffs,$startDate,$endDate)
    {
        $this->dayOff = $dayOffs;
        $this->startDate = Carbon::parse($startDate)->startOfMonth()->toDateString();
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('exports.ai_dayoff', [
            'dayOffs' => $this->dayOff,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
    public function title(): string
    {
        return 'シート1';
    }
}
