<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\FinalClosingHistories;
use App\Models\CashOut;
use Illuminate\Support\Facades\Route;

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
        if (Route::getCurrentRoute()->getActionMethod() == 'update') {
            $cashOut = CashOut::find(request()->route('cash_out'));
            $date = !empty($cashOut) ? date('Y-m', strtotime($cashOut->payment_date)) : '';
            $check = in_array($date, $arrFinalMonth);
            if ($check) {
                $this->attribute = $cashOut;
                return false;
            }
        }
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
        return '「'.$this->attribute.'は既に本締めされています」';
    }
}
