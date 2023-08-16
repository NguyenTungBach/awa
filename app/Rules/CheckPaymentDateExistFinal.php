<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\FinalClosingHistories;

class CheckPaymentDateExistFinal implements Rule
{
    protected string $attribute;

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
        $arrFinalMonth = FinalClosingHistories::get()->pluck('month_year')->toArray();
        $value = date('Y-m', strtotime($value));
        $result = in_array($value, $arrFinalMonth);
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
        return '入金日が最終締め切り時間と重なった';
    }
}
