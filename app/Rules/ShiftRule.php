<?php

namespace App\Rules;

use App\Models\Driver;
use Helper\Common;
use Illuminate\Contracts\Validation\Rule;

class ShiftRule implements Rule
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
        $arrayAttibute = explode('.',$attribute);
        if (in_array($arrayAttibute[4],['start_time','end_time'])){
            $checkTimeCourse = Common::checkTimecourse($value,'time');
            if ($checkTimeCourse['status']!='success') return false;
            return true;
        }

        if ($arrayAttibute[4] == 'break_time'){
            $checkTimeCourse = Common::checkTimecourse($value,'break');
            if ($checkTimeCourse['status']!='success') return false;
            return true;
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
