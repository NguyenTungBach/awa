<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\FinalClosingHistories;

class CheckTempExistsFinalRule implements Rule
{
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($attribute)
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
        $arrFinal = FinalClosingHistories::get()->pluck('month_year')->toArray();
        $result = in_array($value, $arrFinal);
        if ($result) {
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
        return '「'.$this->attribute.'は既に本締めされています」';
    }
}
