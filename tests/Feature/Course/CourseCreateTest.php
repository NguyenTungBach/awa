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

class CourseCreateTest extends TestCase
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

    public function testCourseCreateSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $response = $this->actingAs($user)->json('post', 'api/course', [
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
    public function testCourseCreateCustomer_idEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
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

    public function testCourseCreateCourse_nameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
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

    public function testCourseCreateShip_dateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
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

    public function testCourseCreateShip_dateWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
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

    public function testCourseCreateShip_dateTomorrowDateNow()
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

    public function testCourseCreateStart_dateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
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
                    'start_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseCreateEnd_dateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
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
                    'end_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseCreateBreak_timeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
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
                    'break_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCourseCreateDeparture_placeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
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

    public function testCourseCreateArrival_placeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
        $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'customer_id' => 1,
            'course_name' => "hy 00001",
            'ship_date' => $ship_date,
            'start_date' => "08:00",
            'end_date' => "09:00",
            'break_time' => "08:00",
            'departure_place' => "abc 01",
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

    public function testCourseCreateShip_feeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $ship_date = Carbon::now()->addDay()->format("Y-m-d");
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

    /////////Create End//////////
}
