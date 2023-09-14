<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ClosingDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($month_year)
    {
        $this->month_year = $month_year;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $checkArrayClosingDate = [29,28,30,31];
        // Kiểm tra trường hợp cuối tháng
        if (in_array($value,$checkArrayClosingDate)){
            //Lấy ra ngày của cuối tháng đó
            $checkEndDateOfThisMonthYear = Carbon::parse($this->month_year)->endOfMonth();
            $checkDate = Carbon::parse("$this->month_year-$value");
            if($checkDate->gt($checkEndDateOfThisMonthYear)){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('errors.month_not_day');
    }
}
