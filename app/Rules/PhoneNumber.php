<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    // const PHONE_NUMBER_MINLENGTH = 12;
    const PHONE_NUMBER_MAXLENGTH = 11;

    protected string $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $attribute = 'phone')
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
        $value = str_replace('-', '', $value);

        $length = strlen($value);

        if ($length < self::PHONE_NUMBER_MAXLENGTH) {
            return false;
        }

        if ($length > self::PHONE_NUMBER_MAXLENGTH) {
            return false;
        }
        if (!preg_match('/^[0-9-]+$/D', $value)) {
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
        return __('validation.custom.phone_number.regex', ['attribute' => $this->attribute]);
    }
}
