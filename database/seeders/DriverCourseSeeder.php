<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DriverCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listCourses = Course::$configDataCourseUnique;
        $checkCourse = array_column($listCourses, 'course_code');
        $course = Course::whereIn(Course::COURSE_CODE, $checkCourse)->first();
        if ($course){
            $listDriver = Driver::where(Driver::DRIVER_NAME,'Pikachu')->pluck('driver_code')->toArray();
            foreach ($listDriver as $driver){
                foreach ($checkCourse as $course){
                    DriverCourse::create([
                        'driver_code' => $driver,
                        'course_code' => $course
                    ]);
                }
            }
        }
    }
}
