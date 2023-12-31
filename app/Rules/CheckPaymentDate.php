<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DriverCourse;

class CheckPaymentDate implements Rule
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
        $driverId = request()->route('driver');
        $driverCourses = DriverCourse::where('driver_id', $driverId)->where('date', '<=', $value)->get();
        if ($driverCourses->isEmpty()) {
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
        return 'Payment_date phai lon hon hoac bang date driver course';
    }
}
