<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Course;
use Repository\CourseRepository;

class CourseExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{    
    protected array $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = CourseRepository::getAll($this->input);

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'ID', // id
            '運行日', //ship_date
            '運行名', // course_name
            '始業時間', // start_date
            '終業時間', // end_date
            '休憩時間', // break_time
            '荷主名', // customer_name
            '発地', // departure_place
            '着地', // arrival_place
            '運賃', // ship_fee
            '協力会社支払金額', // associate_company_fee
            '高速道路・フェリー料金', // expressway_fee
            '歩合', // commission
            '食事補助金額', // meal_fee
            'メモ', // note
        ];
    }

    // public function startRow(): int
    // {
    //     return 3;
    // }

    public function styles(Worksheet $sheet)
    {
        // // Merge cells A1 and B1
        // $sheet->mergeCells('A1:B1');
        // // Set the value of cell A1
        // $sheet->setCellValue('A1', '運行情報一覧');

        $headerRange = 'A1:O1';
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ff765e');
        $sheet->getStyle($headerRange)->getFont()->setColor(new Color(Color::COLOR_WHITE));
    }
}
