<?php

namespace Tests\Feature\CashInStatical;

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

class CashInStaticalListTest extends TestCase
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

    public function testCashInStaticalListSuccess()
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

        CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical', [
            'token' => $token,
            'month_year' => "2023-08",
            'field' => "customer_code",
            'sortby' => "desc",
        ])->assertStatus(CODE_SUCCESS);
    }

    /////////List Cash In Statical Start//////////
    public function testCashInStaticalListMonth_yearEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical', [
            'token' => $token,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_year' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCashInStaticalListMonth_yearWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical', [
            'token' => $token,
            'month_year' => "2023/08",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_year' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCashInStaticalListFieldByNotIn()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical', [
            'token' => $token,
            'month_year' => "2023/08",
            'field' => "zxczx",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'field' => [

                    ],

                ],
                "data_error"
            ]);
    }

    public function testCashInStaticalListSortByNotIn()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical', [
            'token' => $token,
            'month_year' => "2023/08",
            'sortby' => "zxczx",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'sortby' => [

                    ],

                ],
                "data_error"
            ]);
    }

    public function testCashInStaticalListExportSuccess()
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

        CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)
            ->withHeader('Authorization', "Bearer ". $token)
            ->post('/api/calendar/setup-data?targetyyyy=2023');
        $this->actingAs($user)->json('get', 'api/cash-in-statical/export-cash-in-statical', [
            'token' => $token,
            'month_year' => "2023-08",
            'field' => "customer_code",
            'sortby' => "desc",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCashInStaticalDetailSuccess()
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

        CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical/1', [
            'token' => $token,
            'month_year' => "2023-08",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCashInStaticalDetailMonth_yearEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical/1', [
            'token' => $token,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_year' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCashInStaticalDetailMonth_yearWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/cash-in-statical/1', [
            'token' => $token,
            'month_year' => "2023/08",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_year' => [

                    ],
                ],
                "data_error"
            ]);
    }

    /////////List Cash In Statical End//////////
}
