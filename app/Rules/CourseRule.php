<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\FinalClosingHistories;

class CourseRule implements Rule
{
    protected string $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $attribute = 'ship_date')
    {
        $this->attribute = $attribute;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $arrFinal = FinalClosingHistories::get()->pluck('month_year')->toArray();
        $value = date('Y-m', strtotime($value));

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
        if (Route::getCurrentRoute()->getActionMethod() == 'import') {
            return __('validation.custom.csv.check_date', ['attribute' => $this->attribute]);
        }

        return __('validation.custom.check_date', ['attribute' => $this->attribute]);
    }
}
