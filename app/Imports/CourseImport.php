<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Rules\CourseRule;
use App\Models\Customer;
use App\Models\Course;
use App\Models\Driver;
use App\Rules\CompareHours;
use Repository\CourseRepository;

class CourseImport implements ToCollection, WithHeadings, WithStartRow, WithValidation
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '運行日', // 0 ship_date
            '運行名', // 1 course_name
            '始業時間', // 2 start_date
            '終業時間', // 3 end_date
            '休憩時間', // 4 break_time
            '荷主名', // 5 customer_id
            '発地', // 6 departure_place
            '着地', // 7 arrival_place
            '運賃', // 8 ship_fee
            '協力会社支払金額', // 9 associate_company_fee
            '高速道路・フェリー料金', // 10 expressway_fee
            '歩合', // 11 commission
            '食事補助金額', // 12 meal_fee
            'メモ', // 13 note


            '運行日', // 0 ship_date 0
            '従業員名', // 1 driver_id
            '車両番号', // 2 vehicle_number
            '始業時間', // 3 start_date 2
            '終業時間', // 4 end_date 3
            '休憩時間', // 5 break_time 4
            '荷主名', // 6 customer_id 5
            '発地', // 7 departure_place 6
            '着地', // 8 arrival_place 7
            '品名', // 9 item_name
            '数量', // 10 quantity
            '単価', // 11 price
            '重量', // 12 weight
            '運賃', // 13 ship_fee 8
            '協力会社支払金額', // 14 associate_company_fee 9
            '高速道路・フェリー料金', // 15 expressway_fee 10
            '歩合', // 16 commission 11
            '食事補助金額', // 17 meal_fee 12
            'メモ', // 18 note 13
        ];
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $arrCustomerName = Customer::get()->pluck('customer_name');

        $rules = [
            // 0 ship_date
            '*.0' => ['required', 'date_format:Y-m-d', new CourseRule($this->headings()[0])],

            // 1 driver_id

            // // 1 course_name
            // '*.1'  => ['required', 'string', 'max:20', 'unique:courses,course_name,NULL,id,deleted_at,NULL',],

            // 2 vehicle_number

            // 3 start_date
            '*.3' => ['required', 'date_format:H:i'],

            // 4 end_date
            '*.4' => ['required', 'date_format:H:i'],

            // 5 break_time
            '*.5' => ['required', 'date_format:H:i'],

            // 6 customer_id
            '*.6' => ['required', Rule::in($arrCustomerName)],

            // 7 departure_place
            '*.7' => ['required', 'string', 'max:20'],

            // 8 arrival_place
            '*.8' => ['required', 'string', 'max:20'],

            // 9 item_name

            // 10 quantity

            // 11 price

            // 12 weight

            // 13 ship_fee
            '*.13' => ['required', 'numeric'],

            // 14 associate_company_fee
            '*.14' => ['nullable', 'numeric'],

            // 15 expressway_fee
            '*.15' => ['nullable', 'numeric'],

            // 16 commission
            '*.16' => ['nullable', 'numeric'],

            // 17 meal_fee
            '*.17' => ['nullable', 'numeric'],

            // 18 note
            '*.18' => ['nullable', 'string', 'max:1000'],

        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            // 0 ship_date
            '*.0.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[0]]),
            '*.0.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[0], 'format' => 'Y-m-d']),

            // // 1 course_name
            // '*.1.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[1]]),
            // '*.1.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[1]]),
            // '*.1.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[1], 'max' => 20]),
            // '*.1.unique'  => __('validation.custom.csv.unique', ['attribute' => $this->headings()[1]]),

            // 1 driver_id

            // 2 vehicle_number

            // 3 start_date
            '*.3.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[3]]),
            '*.3.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[3], 'format' => 'H:i']),

            // 4 end_date
            '*.4.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[4]]),
            '*.4.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[4], 'format' => 'H:i']),

            // 5 break_time
            '*.5.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[5]]),
            '*.5.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[5], 'format' => 'H:i']),

            // 6 customer_id
            '*.6.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[6]]),
            '*.6.in'  => __('validation.custom.csv.in', ['attribute' => $this->headings()[6]]),

            // 7 departure_place
            '*.7.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[7]]),
            '*.7.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[7]]),
            '*.7.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[7], 'max' => 20]),

            // 8 arrival_place
            '*.8.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[8]]),
            '*.8.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[8]]),
            '*.8.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[8], 'max' => 20]),

            // 9 item_name

            // 10 quantity

            // 11 price

            // 12 weight

            // 13 ship_fee
            '*.13.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[13]]),
            '*.13.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[13]]),

            // 14 associate_company_fee
            '*.14.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[14]]),
            '*.14.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[14]]),

            // 15 expressway_fee
            '*.15.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[15]]),
            '*.15.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[15]]),

            // 16 commission
            '*.16.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[16]]),
            '*.16.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[16]]),

            // 17 meal_fee
            '*.17.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[17]]),
            '*.17.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[17]]),

            // 18 note
            '*.18.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[18]]),
            '*.18.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[18], 'max' => 1000]),
        ];
    }

    /**
     *@param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $arrCollection = $collection->toArray();
        $arrCustomer = $this->getCustomerId();
        $arrDriver = $this->getDriverId();
        // $arrVehicle = $this->getVehicleNumber();
        $dataImport = [];
        $errors = [];
        $result = [];

        try {
            DB::beginTransaction();
            for ($i=0; $i < count($arrCollection); $i++) { 
                $row = $collection[$i];
                $rowIndex = $i + $this->startRow();

                // check start_time >= end_time
                $start_time = date('H:i', strtotime('today '.$row[3]));
                $end_time = date('H:i', strtotime('today '.$row[4]));
                if ($start_time >= $end_time) {
                    $errors[] = __('validation.custom.csv.compare_date', ['row' => $rowIndex]);
                }

                $dataImport['customer_id'] = array_search($row[6], $arrCustomer);
                $dataImport['course_name'] = 'driver ' . array_search($row[1], $arrDriver);
                $dataImport['ship_date'] = $row[0];
                $dataImport['driver_id'] = array_search($row[1], $arrDriver);
                // $dataImport['vehicle_number'] = $row[2];
                $dataImport['start_date'] = $row[3];
                $dataImport['end_date'] = $row[4];
                $dataImport['break_time'] = $row[5];
                $dataImport['departure_place'] = $row[7];
                $dataImport['arrival_place'] = $row[8];
                $dataImport['item_name'] = $row[9];
                $dataImport['quantity'] = empty($row[10]) ? 0 : $row[10];
                $dataImport['price'] = empty($row[11]) ? 0 : $row[11];
                $dataImport['weight'] = empty($row[12]) ? 0 : $row[12];
                $dataImport['ship_fee'] = $row[13];
                $dataImport['associate_company_fee'] = empty($row[14]) ? 0 : $row[14];
                $dataImport['expressway_fee'] = empty($row[15]) ? 0 : $row[15];
                $dataImport['commission'] = empty($row[16]) ? 0 : $row[16];
                $dataImport['meal_fee'] = empty($row[17]) ? 0 : $row[17];
                $dataImport['note'] = $row[18];

                if (!count($errors)) {
                    $result = Course::create($dataImport);
                }
            }
            if(count($errors)) {
                $this->data = $errors;
                return $this->data;
            }
            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getCustomerId()
    {
        $customers = Customer::get();
        $result = [];
        foreach ($customers as $key => $value) {
            $result[$value->id] = $value->customer_name;
        }

        return $result;
    }

    public function getDriverId()
    {
        $drivers = Driver::get();
        $result = [];
        foreach ($drivers as $key => $value) {
            $result[$value->id] = $value->driver_name;
        }

        return $result;
    }

    // public function getVehicleNumber()
    // {
    //     $drivers = Driver::get();
    //     $result = [];
    //     foreach ($customers as $key => $value) {
    //         $result[$value->car] = $value->driver_name;
    //     }

    //     return $result;
    // }
}
