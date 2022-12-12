<?php

namespace App\Imports;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class CourseScheduleImport implements ToCollection
{
    public $dataErrors = [];
    private $sheetExecuted = 1;
    public $dataImported = [];
    public function __construct($forDate, $importBeforeSave)
    {
        $this->forDate = $forDate ?? date('Y-m');
        $this->importBeforeSave = $importBeforeSave ?? '';
    }

    public function collection(Collection $rows)
    {
        if ($this->sheetExecuted == 1) {

            $rows = $rows->toArray();
            $firstRow = $rows[0];

            $daysInMonth = \Carbon\Carbon::parse(date('Y-m-d', $this->forDate))->daysInMonth;
            $year = \Carbon\Carbon::parse(date('Y-m-d', $this->forDate))->year;
            $month = \Carbon\Carbon::parse(date('Y-m-d', $this->forDate))->month;
            $firstDay = \Carbon\Carbon::parse(date('Y-m-d', $this->forDate))->firstOfMonth()->toDateString();
            $lastDay = \Carbon\Carbon::parse(date('Y-m-d', $this->forDate))->lastOfMonth()->toDateString();


            $textInvalidFormat = "正しい年月のファイルを取り込んでください。(" . date('Y-m', strtotime(self::getDateCol($firstRow, count($firstRow) - 1))) . ")";
            $checkSheet = self::checkValidSheet($firstRow, $daysInMonth);

            if (isset($checkSheet['error'])) {
                $this->dataErrors = $textInvalidFormat;
            }
            if (self::checkNotInCurrentDateViewed($firstRow, $firstDay, $lastDay)) {
                // $firstDay = self::getDateCol($firstRow,2);
                // $lastDay = self::getDateCol($firstRow,count($firstRow) - 1);
                // $daysInMonth = \Carbon\Carbon::parse($lastDay)->daysInMonth;
                // $year = \Carbon\Carbon::parse($lastDay)->year;
                // $month = \Carbon\Carbon::parse($lastDay)->month;
                $this->dataErrors = $textInvalidFormat;
            }

            if ($this->dataErrors != $textInvalidFormat) {
                $lunarJp = Calendar::whereYear('date', date('Y', $this->forDate))
                    ->whereMonth('date', date('m', $this->forDate))
                    ->pluck('week', 'date')
                    ->all();
                $codes = Course::where(function ($query) {
                    $query->where('flag', Course::COURSE_FLAG_NO)
                        ->orWhereRaw('LENGTH(flag) = 0');
                })
                    ->where('status', Course::COURSE_STATUS_WORK)
                    ->get();
                $codes = $codes->keyBy('course_code')->toArray();
                foreach ($rows as $rowIndex => $row) {
                    $importData = [];

                    if ($rowIndex > 1) {
                        if (!isset($codes[$row[0]])) {
                            if ($row[0] != '') {
                                $this->dataErrors[] = $row[0];
                            }
                            continue;
                        }
                        $course = $codes[$row[0]];
                        $dataRow = [
                            'course_code' => $course['course_code'],
                            'course_name' => $course['course_name'] ?? '',
                            'schedule_date' => '',
                            'status' => 'on',
                            'lunar_jp' => ''
                        ];

                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $date = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                            $dataRow['schedule_date'] = $date;
                            $cellVal = ($row[$day + 1] == "×" || $row[$day + 1] == "x") ? 'off' : 'on';
                            $dataRow['status'] = $cellVal;
                            $dataRow['lunar_jp'] = $lunarJp[$date] ?? '';
                            $importData[$day] = $dataRow;
                        }
                        $affecteds = 0;
                        if (!empty($importData)) {
                            $this->dataImported[] = $importData;
                            if ($this->importBeforeSave) {
                                $affecteds = CourseSchedule::upsert(
                                    $importData,
                                    [CourseSchedule::SCHEDULE_CODE, CourseSchedule::SCHEDULE_DATE],
                                    [CourseSchedule::SCHEDULE_STATUS, CourseSchedule::SCHEDULE_LUNAR_JP]
                                );
                            }


                        }
                    }
                }
            }
            $this->sheetExecuted++;
        }

    }
    private function checkValidSheet($firstRow, $daysInMonth)
    {
        $firstCol = $firstRow[2];
        $lastCol = $firstRow[count($firstRow) - 1];
        $dataCells = count($firstRow) - 2;

        if (!strtotime($firstCol)) {
            $firstCol = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($firstCol)->format('Y-m-d');
        }
        if (!strtotime($lastCol)) {
            $lastCol = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($lastCol)->format('Y-m-d');
        }

        if (!strtotime($firstCol) || !strtotime($lastCol) || date('Y-m', strtotime($firstCol)) != date('Y-m', strtotime($lastCol))) {
            return ['error' => 'days not in a month'];
        }
        if ($dataCells != $daysInMonth) {
            return ['error' => 'not match days in month'];
        }

        return ['firstCol' => $firstCol, 'lastCol' => $lastCol];
    }
    private function getDateCol($firstRow, $col)
    {
        $value = $firstRow[$col];
        if (!strtotime($value)) {
            $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        return $value;
    }
    private function checkNotInCurrentDateViewed($firstRow, $firstDay, $lastDay)
    {
        $firstCol = $firstRow[2];
        $lastCol = $firstRow[count($firstRow) - 1];

        if (!strtotime($firstCol)) {
            $firstCol = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($firstCol)->format('Y-m-d');
        }
        if (!strtotime($lastCol)) {
            $lastCol = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($lastCol)->format('Y-m-d');
        }

        $checkHasError = strtotime($firstCol) != strtotime($firstDay) || strtotime($lastCol) != strtotime($lastDay);
        return $checkHasError;
    }
}
