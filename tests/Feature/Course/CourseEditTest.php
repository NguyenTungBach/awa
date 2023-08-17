<?php

namespace Tests\Feature\Course;

use App\Models\CashIn;
use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourseEditTest extends TestCase
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
    }

    public function testCourseEditSuccess()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(CODE_SUCCESS);
    }

    /////////Create Start//////////
    public function testCourseEditCustomer_idNotFound()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            "customer_id"=> 99,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditCourse_nameMax20()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            "course_name"=> "Course name 6zxczxczxczxzxczjxkcnzkxjcnzxczxcjzkxncjzkxc",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'course_name' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditShip_dateWrongFormat()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => "2023/01-08",
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'ship_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditShip_dateTomorrowDateNow()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->subDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'ship_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditStart_dateLessThanEndate()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            "start_date"=> "09:00",
            'end_date' => "08:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditDeparture_placeMax20()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            "departure_place"=> "Departure place 06zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz",
            'arrival_place' => "abc 02",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'departure_place' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditArrival_placeMax20()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz",
            'ship_fee' => 1000,
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'arrival_place' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditShip_fee20Max()
    {
       $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            "ship_fee"=> "6000000000000000000000000000000000000000000000",
            'associate_company_fee' => 1000,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'ship_fee' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditAssociate_company_fee20Max()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            "ship_fee"=> 6000,
            'associate_company_fee' => "10000000000000000000000000000000000000000",
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'associate_company_fee' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditExpressway_fee20Max()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            "ship_fee"=> 6000,
            'associate_company_fee' => 60,
            'expressway_fee' => "1000000000000000000000000000000000000000000000000000000000",
            'commission' => 5000,
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'expressway_fee' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditCommission20Max()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            "ship_fee"=> 6000,
            'associate_company_fee' => 60,
            'expressway_fee' => 1000,
            'commission' => "50000000000000000000000000",
            'meal_fee' => 750,
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'commission' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseEditMeal_fee20Max()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $course = Course::create([
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

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('put', "api/course/$course->id", [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
            'arrival_place' => "abc 02",
            "ship_fee"=> 6000,
            'associate_company_fee' => 60,
            'expressway_fee' => 1000,
            'commission' => 5000,
            'meal_fee' => "7500000000000000000000000",
            'note' => "test course",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'meal_fee' => [

                    ],
                ],
                "data_error"
            ]);
    }
    /////////Create End//////////
}
