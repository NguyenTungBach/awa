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
        $dem = 0;
        foreach ($arrId as $key => $value) {
            $dem = $dem + 10;
            // Lấy ngẫu nhiên trong khoảng hôm nay đến 7 ngày trước
            $randomNumberOfDays = rand(0, 7);
            $aboutSevenDaysAgo = Carbon::now()->subDays($randomNumberOfDays);

            Course::factory()->create([
                'customer_id' => $value,
                'course_name' => 'Course name ' . $value,
                'ship_date' => $aboutSevenDaysAgo->format("Y-m-d"),
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 0' . $value,
                'arrival_place' => 'Arrival place 0 ' . $value,
                'ship_fee' => '5000',
                'associate_company_fee' => $dem,
                'expressway_fee' => $dem,
                'commission' => $dem,
                'meal_fee' => $dem,
                'note' => NULL,
            ]);
        }
    }
}
