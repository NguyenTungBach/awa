<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Application;
use Repository\CourseRepository;

class CourseSeeder extends Seeder
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepositorys)
    {
        $this->courseRepository = $courseRepositorys;
    }

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
        if (!$course) {
            foreach ($listCourses as $cours) {
                $this->courseRepository->create($cours);
            }
        }

    }
}
