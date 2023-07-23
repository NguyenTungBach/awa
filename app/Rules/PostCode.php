<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PostCode implements Rule
{
    const POSTAL_CODE_LENGTH = 8;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $attribute = 'post_code')
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
        if (strlen($value) < self::POSTAL_CODE_LENGTH) {
            return false;
        }
        if (strlen($value) > self::POSTAL_CODE_LENGTH) {
            return false;
        }
        if (!preg_match('%^\d{3}-\d{4}$%i', $value)) {
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
        return __('validation.regex', ['attribute' => $this->attribute]);
    }
}
