<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class SurveyResultExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $surveyResult;
    public function __construct($surveyResult)
    {
        $this->surveyResult = $surveyResult;
    }
    public function collection()
    {
        return new Collection($this->surveyResult);
    }
}
