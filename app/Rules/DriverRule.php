<?php

namespace App\Rules;

use App\Models\Driver;
use Illuminate\Contracts\Validation\Rule;

class DriverRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if ($attribute == 'day_of_week'){
            $arrayDayOfWeek = [MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY];
            $arrayValueDayOfWeek = explode(',',$value);
            if (count(array_unique($arrayValueDayOfWeek)) != count($arrayValueDayOfWeek)) return false;
            foreach ($arrayValueDayOfWeek as $key => $valueDay){
                if (!in_array($valueDay,$arrayDayOfWeek)) return false;
            }
        }
        if ($attribute == 'flag'){
            $arrayFlag = [Driver::DRIVER_FLAG_POSITION_LEADER,Driver::DRIVER_FLAG_POSITION_FULL_TIME,Driver::DRIVER_FLAG_POSITION_PART_TIME];
            $arrayValueFlag = explode(',',$value);
            foreach ($arrayValueFlag as $key => $valueDay){
                if (!in_array($valueDay,$arrayFlag)) return false;
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
        return [
            'in' => trans('validation.in'),
        ];
    }
}
