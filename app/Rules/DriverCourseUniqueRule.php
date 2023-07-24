<?php

namespace App\Rules;

use App\Models\Driver;
use App\Models\DriverCourse;
use Illuminate\Contracts\Validation\Rule;

class DriverCourseUniqueRule implements Rule
{
    protected $date;
    protected $driver_id;
    protected $course_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $driver_id, $course_id)
    {
        $this->date = $date;
        $this->driver_id = $driver_id;
        $this->course_id = $course_id;
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
        return !DriverCourse::where('driver_id', $this->driver_id)
            ->where('course_id', $this->course_id)
            ->where('date', $this->date)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("errors.unique" ,[
            "attribute"=> "driver_id, course_id, and date"
        ]);
    }
}
