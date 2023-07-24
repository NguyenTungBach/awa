<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ShipDateRule implements Rule
{
    protected $value_end;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($value_end)
    {
        $this->value_end = $value_end;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value_start)
    {
        if (empty($this->value_end)) {
            return true;
        }
        if ($value_start > $this->value_end) {
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
        return '開始日は終了日よりも前の日付である必要があります';
    }
}
