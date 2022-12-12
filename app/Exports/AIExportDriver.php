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

class AIExportDriver implements FromView, Responsable ,WithTitle
{
    use Exportable;


    private $writerType = Excel::XLSX;
    protected $driver;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct($drivers, $shiftData)
    {
        $this->driver = $drivers;
        $this->shiftData = $shiftData ?? [];
    }

    public function view(): View
    {
        $convertDataDriver = [];
        foreach ($this->driver  as $driver){
            $workerTimes = isset($this->shiftData[$driver['driver_code']], $this->shiftData[$driver['driver_code']]['reports']) ? $this->shiftData[$driver['driver_code']]['reports']['total_time']: 0;
            $workerDays = isset($this->shiftData[$driver['driver_code']], $this->shiftData[$driver['driver_code']]['reports']) ? $this->shiftData[$driver['driver_code']]['reports']['working_days']: 0;
            $convertDataDriver[] = [
                'driver_code' => str_repeat('0', 15 - strlen($driver['driver_code'])) . $driver['driver_code'],
                'workedTimes' => $workerTimes,
                'workedDays' => $workerDays,
                'working_day' => $driver['working_day'],
                'mon' => \Helper\Common::getDataDayOfWeek($driver,'mon'),
                'tue' => \Helper\Common::getDataDayOfWeek($driver,'tue'),
                'wed' => \Helper\Common::getDataDayOfWeek($driver,'wed'),
                'thu' => \Helper\Common::getDataDayOfWeek($driver,'thu'),
                'fri' => \Helper\Common::getDataDayOfWeek($driver,'fri'),
                'sat' => \Helper\Common::getDataDayOfWeek($driver,'sat'),
                'sun' => \Helper\Common::getDataDayOfWeek($driver,'sun'),
            ];
        }
        return view('exports.ai_driver',[
           'drivers' => $convertDataDriver
        ]);
    }

    public function title(): string
    {
        return 'シート1';
    }
}
