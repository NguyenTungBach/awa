<?php

namespace Tests\Feature\CashIn;

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

class CashInUpdateTest extends TestCase
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

    public function testCashInUpdateSuccess()
    {
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $dayForCourse7 = Carbon::now()->addDays(2)->format("Y-m-d");
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

        $course7 = Course::create([
            "customer_id"=> 5,
            "course_name"=> "Course name 7",
            "ship_date"=> $dayForCourse7,
            "start_date"=> "09:00",
            "break_time"=> "00:00",
            "end_date"=> "10:00",
            "departure_place"=> "Departure place 07",
            "arrival_place"=> "Arrival place 0 7",
            "ship_fee"=> 7000,
            "associate_company_fee"=> 70,
            "expressway_fee"=> 70,
            "commission"=> 70,
            "meal_fee"=> 70,
        ]);

        $cashIn = CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('put', 'api/cash-in'."/$cashIn->id", [
            'token' => $token,
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse7,
        ])->assertStatus(CODE_SUCCESS);
    }

    /////////Update Start//////////
    public function testCashInUpdateCustomer_idEmpty()
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

        $cashIn = CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('put', 'api/cash-in'."/$cashIn->id", [
            'token' => $token,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
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

    public function testCashInUpdateCash_inEmpty()
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
        $cashIn = CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('put', 'api/cash-in'."/$cashIn->id", [
            'token' => $token,
            'customer_id' => 1,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'cash_in' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCashInUpdatePayment_methodEmpty()
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

        $cashIn = CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('put', 'api/cash-in'."/$cashIn->id", [
            'token' => $token,
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_method' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCashInUpdatePayment_dateEmpty()
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
        $cashIn = CashIn::create([
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'payment_date' => $dayForCourse6,
            'status' => 1,
        ]);

        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('put', 'api/cash-in'."/$cashIn->id", [
            'token' => $token,
            'customer_id' => 1,
            'cash_in' => 1000,
            'payment_method' => 1,
            'status' => 1,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    /////////Update End//////////
}
