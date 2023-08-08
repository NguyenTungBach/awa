<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Repository\CashOutStatisticalRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Models\Calendar;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CashOutStatisticalExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected array $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function view(): View
    {
        $this->input['order_by'] = Arr::get($this->input, 'order_by', 'drivers.id');
        $this->input['sort_by'] = Arr::get($this->input, 'sort_by', 'desc');
        $this->input['month_line'] = Arr::get($this->input, 'month_line', Carbon::now()->format('Y-m'));

        $startOfMonth = Carbon::create($this->input['month_line'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($this->input['month_line'])->endOfMonth()->format('Y-m-d');

        $startDateJapan = Calendar::where('date', $startOfMonth)->first()->week;
        $endDateJapan = Calendar::where('date', $endOfMonth)->first()->week;

        $startDate = Carbon::create($this->input['month_line'])->startOfMonth()->format('Y年m月d日');
        $endDate = Carbon::create($this->input['month_line'])->endOfMonth()->format('Y年m月d日');

        $title = $startDate.'('.$startDateJapan.')'.'〜'.$endDate.'('.$endDateJapan.')';
        return view('exports.cash_out_statistical', [
            'result' => CashOutStatisticalRepository::getAllCashOutStatisticalByDriver($this->input),
            'title' => $title,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $headerRange = 'A3:G3';
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ff765e');
        $sheet->getStyle($headerRange)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle($headerRange)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(25);

        return [
            'A1' => [
                'font' => [
                    'size' => 20, // Set the font size here
                    'name' => 'Calibri',
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
            'A3:G3' => [
                'font' => [
                    'size' => 11, // Set the font size here
                    'name' => 'Calibri',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'cdcdcd'],
                    ],
                ],
            ],
        ];
    }
}
