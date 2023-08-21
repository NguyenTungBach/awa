<?php

namespace Tests\Feature\CashOut;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth;
use Faker\Generator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Faker\Factory as Faker;
use App\Models\CashOut;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

class CashOutDisbursementTest extends TestCase
{
    // use RefreshDatabase;

    protected $cashOut;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=UserSeeder');
        $user = User::first();
        if (!$user) {
            $user = User::factory()->count(5)->create();
        }
        // create driver
        $this->artisan('db:seed --class=DriverSeeder');
        DB::table('drivers')->insert([
            [
                'driver_code' => '0005',
                'driver_name' => 'Associate 5',
                'type' => 4,
                'start_date' => '2022-09-19',
                'car' => 'Truck',
                'status' => 1,
            ],
            [
                'driver_code' => '0006',
                'driver_name' => 'Associate 6',
                'type' => 4,
                'start_date' => '2022-09-19',
                'car' => 'Container',
                'status' => 1,
            ],
            [
                'driver_code' => '0007',
                'driver_name' => 'Associate 7',
                'type' => 4,
                'start_date' => '2022-09-19',
                'car' => 'Labo',
                'status' => 1,
            ],
            [
                'driver_code' => '0008',
                'driver_name' => 'Associate 8',
                'type' => 4,
                'start_date' => '2022-09-19',
                'car' => 'Grap',
                'status' => 1,
            ],
        ]);
        // create course
        $this->artisan('db:seed --class=CourseSeeder');
        DB::table('courses')->insert([
            [
                'customer_id' => 1,
                'course_name' => 'Course name 1',
                'ship_date' => '2023-08-01',
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 01',
                'arrival_place' => 'Arrival place 01',
                'ship_fee' => '50000',
                'associate_company_fee' => '20000',
                'expressway_fee' => 0,
                'commission' => 0,
                'meal_fee' => 0,
                'note' => NULL,
            ],
            [
                'customer_id' => 1,
                'course_name' => 'Course name 2',
                'ship_date' => '2023-08-01',
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 02',
                'arrival_place' => 'Arrival place 02',
                'ship_fee' => '800',
                'associate_company_fee' => '650',
                'expressway_fee' => 0,
                'commission' => 0,
                'meal_fee' => 0,
                'note' => NULL,
            ],
            [
                'customer_id' => 1,
                'course_name' => 'Course name 3',
                'ship_date' => '2023-08-01',
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 03',
                'arrival_place' => 'Arrival place 03',
                'ship_fee' => '1500',
                'associate_company_fee' => '900',
                'expressway_fee' => 0,
                'commission' => 0,
                'meal_fee' => 0,
                'note' => NULL,
            ],
            [
                'customer_id' => 1,
                'course_name' => 'Course name 4',
                'ship_date' => '2023-08-01',
                'start_date' => '09:00',
                'end_date' => '10:00',
                'break_time' => '00:00',
                'departure_place' => 'Departure place 04',
                'arrival_place' => 'Arrival place 04',
                'ship_fee' => '1000',
                'associate_company_fee' => '500',
                'expressway_fee' => 0,
                'commission' => 0,
                'meal_fee' => 0,
                'note' => NULL,
            ],
        ]);
        // create cash out
        DB::table('cash_outs')->truncate();
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        DB::table('cash_outs')->insert([
            [
                'driver_id' => $driver->id,
                'payment_date' => '2023-08-01',
                'cash_out' => '450',
                'payment_method' => '2',
                'note' => NULL,
            ],
            [
                'driver_id' => $driver->id,
                'payment_date' => '2023-07-01',
                'cash_out' => '500',
                'payment_method' => '1',
                'note' => NULL,
            ],
            [
                'driver_id' => $driver->id,
                'payment_date' => '2023-08-20',
                'cash_out' => '800',
                'payment_method' => '2',
                'note' => NULL,
            ],
            [
                'driver_id' => $driver->id,
                'payment_date' => '2023-08-25',
                'cash_out' => '650',
                'payment_method' => '1',
                'note' => NULL,
            ],
            [
                'driver_id' => $driver->id,
                'payment_date' => '2023-08-25',
                'cash_out' => '300',
                'payment_method' => '1',
                'note' => NULL,
            ],
        ]);
    }

    public function testCashOutDisbursementListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCashOutDisbursementListSuccessWithOrderBySortByMonthLine()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $orderBy = 'driver_code';
        $sortBy = 'desc';
        $monthLine = '2023-08';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?order_by='.$orderBy.'&sort_by='.$sortBy.'&month_line='.$monthLine.'&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCashOutDisbursementListMonthLineWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $monthLine = '2023-08-01';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?month_line='.$monthLine.'&token=' . $token)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_line' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDisbursementListMonthLineEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $monthLine = '';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?month_line='.$monthLine.'&token=' . $token)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_line' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDisbursementListOrderByWrongRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $orderBy = 'driver_id';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?order_by='.$orderBy.'&token=' . $token)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'order_by' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDisbursementListSortByWrongRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $sortBy = 'abc';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical?sort_by='.$sortBy.'&token=' . $token)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'sort_by' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDisbursementDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical/'.$driver->id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCashOutDisbursementDetailSuccessWithMonthLine()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $monthLine = '2023-08';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical/'.$driver->id.'?month_line='.$monthLine, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCashOutDisbursementDetailMonthLineEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $monthLine = '';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical/'.$driver->id.'?month_line='.$monthLine, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_line' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDisbursementDetailMonthLineWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $monthLine = '2023-08-01';

        $response = $this->actingAs($user)->get('api/driver-cash-out-statistical/'.$driver->id.'?month_line='.$monthLine, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'month_line' => [

                    ],
                ],
            ]);
    }
}
