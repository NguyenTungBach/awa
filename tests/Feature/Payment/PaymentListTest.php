<?php

namespace Tests\Feature\Payment;

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

class PaymentListTest extends TestCase
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

    public function testPaymentListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/payment', [
            'token' => $token,
            'order_by' => "drivers.driver_code",
            'sort_by' => "asc",
            'month_year' => "2023-08",
        ])->assertStatus(CODE_SUCCESS);
    }

    /////////List Start//////////
    public function testPaymentListOrder_byNotIn()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/payment', [
            'token' => $token,
            'order_by' => "zxczxc",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'order_by' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testPaymentListSort_byNotIn()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/payment', [
            'token' => $token,
            'sort_by' => "zxczxc",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'sort_by' => [

                    ],
                ],
                "data_error"
            ]);
    }

    /////////List End//////////
}
