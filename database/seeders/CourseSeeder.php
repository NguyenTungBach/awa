<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Application;
use Repository\CourseRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        DB::table('courses')->truncate();
        $arrId = Customer::get()->pluck('id');
        foreach ($arrId as $key => $value) {
            Course::factory()->create([
                'customer_id' => $value,
                'course_name' => 'Course name ' . $value,
                'ship_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 0' . $value,
                'arrival_place' => 'Arrival place 0 ' . $value,
                'ship_fee' => '5000',
                'associate_company_fee' => '0',
                'expressway_fee' => '0',
                'commission' => '0',
                'meal_fee' => '0',
                'note' => NULL,
            ]);
        }
    }
}
