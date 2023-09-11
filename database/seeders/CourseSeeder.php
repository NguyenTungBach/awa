<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
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
        // 7 trường hợp đặc biệt start
        // Nếu rơi vào trong các id Course này sẽ phải xử lý riêng biệt
        // 1.Trường hợp wait
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "待機", // wait
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '00:00',
            'end_date' => '00:00',
            'break_time' => '00:00',
            'departure_place' => 'wait',
            'arrival_place' => 'wait',
            'item_name' => 'wait',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);

        // 2.Trường hợp leader/Chief
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "社内業務", // leader/Chief
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '09:00',
            'end_date' => '10:00',
            'break_time' => '00:00',
            'departure_place' => 'leader/Chief',
            'arrival_place' => 'leader/Chief',
            'item_name' => 'wait',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);

        // 3.Trường hợp working status
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "手間", // working status
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '09:00',
            'end_date' => '10:00',
            'break_time' => '00:00',
            'departure_place' => 'this is a working status, not day-off',
            'arrival_place' => 'this is a working status, not day-off',
            'item_name' => 'working status',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);
        // 4.Trường hợp holiday
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "公休", // holiday
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '00:00',
            'end_date' => '00:00',
            'break_time' => '00:00',
            'departure_place' => 'holiday',
            'arrival_place' => 'holiday',
            'item_name' => 'holiday',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);

        // 5.Trường hợp day-off request
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "希望休", // day-off request
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '00:00',
            'end_date' => '00:00',
            'break_time' => '00:00',
            'departure_place' => 'day-off request',
            'arrival_place' => 'day-off request',
            'item_name' => 'day-off request',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);

        // 6.Trường hợp paid
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "有給休暇", // paid
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '00:00',
            'end_date' => '00:00',
            'break_time' => '00:00',
            'departure_place' => 'paid',
            'arrival_place' => 'paid',
            'item_name' => 'paid',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);

        // 7.Trường hợp half off
        Course::factory()->create([
            'customer_id' => 0,
            'driver_id' => 0,
            'course_name' => "半休", // half off
            'ship_date' => Carbon::now()->format("Y-m-d"),
            'start_date' => '00:00',
            'end_date' => '00:00',
            'break_time' => '00:00',
            'departure_place' => 'half off',
            'arrival_place' => 'half off',
            'item_name' => 'half off',
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
            'ship_fee' => 0,
            'associate_company_fee' => 0,
            'expressway_fee' => 0,
            'commission' => 0,
            'meal_fee' => 0,
            'status' => 2,
        ]);
        // 7 trường hợp đặc biệt end
        $arrId = Customer::get()->pluck('id');
        $arrDriverId = Driver::get()->pluck('id')->toArray();
        $dem = 0;
        foreach ($arrId as $key => $value) {
            $keyRandom = array_rand($arrDriverId);
            $valueRandom = $arrDriverId[$keyRandom];
            $dem = $dem + 10;
            // Lấy ngẫu nhiên trong khoảng hôm nay đến 7 ngày trước
            $randomNumberOfDays = rand(0, 7);
            $aboutSevenDaysAgo = Carbon::now()->subDays($randomNumberOfDays);

            $course = Course::factory()->create([
                'customer_id' => $value,
                'driver_id' => $valueRandom,
                'course_name' => 'Course name ' . $value,
                'ship_date' => $aboutSevenDaysAgo->format("Y-m-d"),
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 0' . $value,
                'arrival_place' => 'Arrival place 0 ' . $value,
                'item_name' => 'Item name 0' . $value,
                'quantity' => $dem,
                'price' => $dem,
                'weight' => $dem,
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
