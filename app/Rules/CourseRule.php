<?php

namespace App\Rules;

use App\Models\Course;
use App\Models\Driver;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;

class CourseRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if ($attribute == 'course_name') {
            $id = Route::getCurrentRoute()->course;
            $course = Course::where(Course::COURSE_NAME, $value)->where('id', '!=', $id)->first();
            if ($course) {
                return false;
            }
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
        return [
            'course_name.*' => 'このコース名は既に登録されています。',
        ];
    }
}
