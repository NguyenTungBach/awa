<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime;

class CompareHours implements Rule
{
    protected $end_date;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($end_date)
    {
        $this->end_date = $end_date;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $start_date)
    {
        $start_time = date('H:i', strtotime('today '.$start_date));
        $end_time = date('H:i', strtotime('today '.$this->end_date));

        if ($start_time >= $end_time) {
            return false;
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
        return __('validation.custom.compare_date');
    }
}
