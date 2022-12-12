<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\View\View;

class ShiftGrade implements FromView
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
        $this->items = $items;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.shift-grade-tab', [
            'items' => $this->items,
            'data' => $this->data
        ]);
    }

}
