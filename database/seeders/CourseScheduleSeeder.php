<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course = Course::where(function ($query) {
                    $query->where('flag', Course::COURSE_FLAG_NO)
                            ->orWhereRaw('LENGTH(flag) = 0');
                })
                ->where('status', Course::COURSE_STATUS_WORK)
                ->where('course_code', '00001')
                ->first();
        if (!$course) {
            Course::create([
                'flag' => Course::COURSE_FLAG_NO,
                'course_code' => '00001',
                'course_name' => 'test',
                'start_date' => date('Y-m-d'),
                'start_time' => '6:00',
                'end_time' => '21:00',
                Course::COURSE_BREAK_TIME => '1.5',
                Course::COURSE_POINT => '1',
                'end_date' => '',
                'status' => 'on'
            ]);
        }
        if (!CourseSchedule::first()){
            $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
            $firstDay = \Carbon\Carbon::now()->firstOfMonth()->toDateString();

            foreach (range(0, $daysInMonth - 1) as $d) {
                $date = date('Y-m-d', strtotime($firstDay . ' + ' . $d . ' days'));
                $lunar_jps = Calendar::pluck('week', 'date')->all();
                CourseSchedule::create([
                    'course_code' => 'axNc',
                    'course_name' => 'Maryse Auer',
                    'schedule_date' => $date,
                    'status' => 'on',
                    'lunar_jp' => 'a'//$lunar_jps[$date],
                ]);
            }

        }

    }
}
