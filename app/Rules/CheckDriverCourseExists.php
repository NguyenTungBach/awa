<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DriverCourse;

class CheckDriverCourseExists implements Rule
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
        $courseId = request()->route('course');
        $existsCourse = DriverCourse::where('course_id', $courseId)->get();
        if ($existsCourse->isEmpty()) {
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
        return 'NOT FOUND COURSE';
    }
}
