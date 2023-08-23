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

            // 1 course_name
            '*.1'  => ['required', 'string', 'max:20', 'unique:courses,course_name,NULL,id,deleted_at,NULL',],

            // 2 start_date
            '*.2' => ['required', 'date_format:H:i'],

            // 3 end_date
            '*.3' => ['required', 'date_format:H:i'],

            // 4 break_time
            '*.4' => ['required', 'date_format:H:i'],

            // 5 customer_id
            '*.5' => ['required', Rule::in($arrCustomerName)],

            // 6 departure_place
            '*.6' => ['required', 'string', 'max:20'],

            // 7 arrival_place
            '*.7' => ['required', 'string', 'max:20'],

            // 8 ship_fee
            '*.8' => ['required', 'numeric'],

            // 9 associate_company_fee
            '*.9' => ['nullable', 'numeric'],

            // 10 expressway_fee
            '*.10' => ['nullable', 'numeric'],

            // 11 commission
            '*.11' => ['nullable', 'numeric'],

            // 12 meal_fee
            '*.12' => ['nullable', 'numeric'],

            // 13 note
            '*.13' => ['nullable', 'string', 'max:1000'],

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

            // 1 course_name
            '*.1.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[1]]),
            '*.1.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[1]]),
            '*.1.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[1], 'max' => 20]),
            '*.1.unique'  => __('validation.custom.csv.unique', ['attribute' => $this->headings()[1]]),
            // 2 start_date
            '*.2.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[2]]),
            '*.2.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[2], 'format' => 'H:i']),

            // 3 end_date
            '*.3.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[3]]),
            '*.3.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[3], 'format' => 'H:i']),

            // 4 break_time
            '*.4.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[4]]),
            '*.4.date_format'  => __('validation.custom.csv.date_format', ['attribute' => $this->headings()[4], 'format' => 'H:i']),

            // 5 customer_id
            '*.5.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[5]]),
            '*.5.in'  => __('validation.custom.csv.in', ['attribute' => $this->headings()[5]]),

            // 6 departure_place
            '*.6.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[6]]),
            '*.6.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[6]]),
            '*.6.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[6], 'max' => 20]),
            // 7 arrival_place
            '*.7.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[7]]),
            '*.7.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[7]]),
            '*.7.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[7], 'max' => 20]),
            // 8 ship_fee
            '*.8.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[8]]),
            '*.8.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[8]]),
            // 9 associate_company_fee
            '*.9.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[9]]),
            '*.9.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[9]]),
            // 10 expressway_fee
            '*.10.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[10]]),
            '*.10.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[10]]),
            // 11 commission
            '*.11.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[11]]),
            '*.11.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[11]]),
            // 12 meal_fee
            '*.12.required'  => __('validation.custom.csv.required', ['attribute' => $this->headings()[12]]),
            '*.12.numeric'  => __('validation.custom.csv.numeric', ['attribute' => $this->headings()[12]]),
            // 13 note
            '*.13.string'  => __('validation.custom.csv.string', ['attribute' => $this->headings()[13]]),
            '*.13.max'  => __('validation.custom.csv.max', ['attribute' => $this->headings()[13], 'max' => 1000]),
        ];
    }

    /**
     *@param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $arrCollection = $collection->toArray();
        $arrCustomer = $this->getCustomerId();
        $dataImport = [];
        $errors = [];
        $result = [];

        try {
            DB::beginTransaction();
            for ($i=0; $i < count($arrCollection); $i++) { 
                $row = $collection[$i];
                $rowIndex = $i + $this->startRow();

                // check start_time >= end_time
                $start_time = date('H:i', strtotime('today '.$row[2]));
                $end_time = date('H:i', strtotime('today '.$row[3]));
                if ($start_time >= $end_time) {
                    $errors[] = __('validation.custom.csv.compare_date', ['row' => $rowIndex]);
                }

                $dataImport['customer_id'] = array_search($row[5], $arrCustomer);
                $dataImport['course_name'] = $row[1];
                $dataImport['ship_date'] = $row[0];
                $dataImport['start_date'] = $row[2];
                $dataImport['end_date'] = $row[3];
                $dataImport['break_time'] = $row[4];
                $dataImport['departure_place'] = $row[6];
                $dataImport['arrival_place'] = $row[7];
                $dataImport['ship_fee'] = $row[8];
                $dataImport['associate_company_fee'] = empty($row[9]) ? 0 : $row[9];
                $dataImport['expressway_fee'] = empty($row[10]) ? 0 : $row[10];
                $dataImport['commission'] = empty($row[11]) ? 0 : $row[11];
                $dataImport['meal_fee'] = empty($row[12]) ? 0 : $row[12];
                $dataImport['note'] = $row[13];

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
}
