<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Excel;

class ReportExport implements FromView
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'reportDriver.xlsx';

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

    public function __construct($items, $viewDate, $statusView)
    {
        $this->items = $items;
        $this->viewDate = $viewDate;
        $this->statusView = $statusView;

    }

    public function view(): View
    {
        return view('exports.report', [
            'items' => $this->items ?? [],
            'viewDate' => $this->viewDate,
            'statusView' => $this->statusView
        ]);
    }
}
