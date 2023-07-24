<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime;
use Carbon\Carbon;

class TimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
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
        $arrTime = explode(':',$value);
        $arrMinute = [00, 15, 30, 45];
        if (in_array($arrTime[1], $arrMinute) == false) {
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
        return __('validation.custom.check_minute', ['attribute' => $this->attribute]);
    }
}
