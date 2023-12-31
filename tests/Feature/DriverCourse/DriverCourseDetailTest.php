<?php

namespace Tests\Feature\DriverCourse;

use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DriverCourseDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $user = User::where('user_code', '=', '1122')->first();
        if (!$user) {
            User::CreateDriver('1122', 'Super Admin', 'abc12345678', 'admin');
        }
        $driver = Driver::first();
        if (!$driver) {
            $driver = Driver::factory()->create([
                    "type" => 1,
                    "driver_code" => "00055",
                    "driver_name" => "Bach",
                    "car" => "LamBo",
                    "start_date" => "2022-08-20",
                    "note" => "thoi roi ta da xa nhau",
                ]
            );
        }
        $course = Course::first();

//        $driverCourse = DriverCourse::where(DriverCourse::DRIVER_COURSE_DRIVER_CODE, $driver->driver_code)
//            ->where(DriverCourse::DRIVER_COURSE_COURSE_CODE, $course->course_code)->first();
//        if (!$driverCourse) {
//            $course = DriverCourse::create([
//                    'driver_code' => $driver->driver_code,
//                    'course_code' => $course->course_code,
//                    'is_checked' => "no",
//                ]
//            );
//        }

    }

//    public function testDriverCourseListSuccess()
//    {
//        $user = User::where('user_code', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $driver = Driver::first();
//        $id = $driver->id;
//        $response = $this->actingAs($user)->get('api/driver-course/list/' . $id , ['HTTP_Authorization' => 'Bearer' . $token])
//            ->assertStatus(CODE_SUCCESS);
//    }

    /////////Detail Start//////////
    public function testDriverCourseDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $driver=Driver::find(1);
        $course6 = Course::create([
            "customer_id"=> 5,
            "course_name"=> "Course name 6",
            "ship_date"=> $dayForCourse6,
            "start_date"=> "09:00",
            "break_time"=> "00:00",
            "end_date"=> "10:00",
            "departure_place"=> "Departure place 06",
            "arrival_place"=> "Arrival place 0 6",
            "ship_fee"=> 6000,
            "associate_company_fee"=> 60,
            "expressway_fee"=> 60,
            "commission"=> 60,
            "meal_fee"=> 60,
        ]);
        $driver_course = DriverCourse::create([
            "driver_id" => $driver->id,
            "course_id" => $course6->id,
            "date" => $course6->ship_date,
            "start_time"=> "09:00",
            "break_time"=> "00:00",
            "end_time"=> "10:00",
            "status" => 1,
        ]);
        $response = $this->actingAs($user)->json('get', "api/driver-course/$driver_course->driver_id", [
            'token' => $token,
            'date' => $driver_course->date,
        ])->assertStatus(CODE_SUCCESS)
            ->assertJsonStructure([
                "code",
                "data" => [
                    "driver_id",
                    "date",
                    'listShift' => [
                        [
                            "id",
                            "driver_id",
                            "course_id",
                            "start_time",
                            "end_time",
                            "break_time",
                            "date",
                            "status",
                            "created_at",
                            "updated_at",
                            "deleted_at",
                            "course" => [
                                "id",
                                "customer_id",
                                "course_name",
                                "ship_date",
                                "start_date",
                                "break_time",
                                "end_date",
                                "departure_place",
                                "arrival_place",
                                "ship_fee",
                                "associate_company_fee",
                                "expressway_fee",
                                "commission",
                                "meal_fee",
                                "status",
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testDriverCourseDetailNotFounData()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $driver=Driver::find(1);
        $course6 = Course::create([
            "customer_id"=> 5,
            "course_name"=> "Course name 6",
            "ship_date"=> $dayForCourse6,
            "start_date"=> "09:00",
            "break_time"=> "00:00",
            "end_date"=> "10:00",
            "departure_place"=> "Departure place 06",
            "arrival_place"=> "Arrival place 0 6",
            "ship_fee"=> 6000,
            "associate_company_fee"=> 60,
            "expressway_fee"=> 60,
            "commission"=> 60,
            "meal_fee"=> 60,
        ]);
        $driver_course = DriverCourse::create([
            "driver_id" => $driver->id,
            "course_id" => $course6->id,
            "date" => $course6->ship_date,
            "start_time"=> "09:00",
            "break_time"=> "00:00",
            "end_time"=> "10:00",
            "status" => 1,
        ]);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('get', "api/driver-course/99", [
            'date' => $driver_course->date,
        ])->assertStatus(CODE_SUCCESS)
            ->assertJsonStructure([
                "code",
                "data" => [
                    "driver_id",
                    "date",
                    'listShift' => []
                ],
            ]);
    }
    /////////Detail End//////////
}
