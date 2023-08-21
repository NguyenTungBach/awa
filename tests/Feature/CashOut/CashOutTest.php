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

class CashOutTest extends TestCase
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
        $this->artisan('db:seed --class=DriverSeeder');
        $driver = Driver::first();
        if (!$driver) {
            $driver = Driver::factory()->count(5)->create();
        }
    }

    public function testCashOutListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $filterMonth = '2023-08';
        $response = $this->actingAs($user)->get('api/driver/'.$driver->id.'/cash-out?filter_month='.$filterMonth.'&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCashOutListFail()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $filterMonth = '2023-08-01';
        $response = $this->actingAs($user)->get('api/driver/'.$driver->id.'/cash-out?filter_month='.$filterMonth.'&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateCashOutSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-24',
            'cash_out' => '1000',
            'payment_method' => '1',
            'note' => NULL,
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateCashOutPaymentDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '',
            'cash_out' => '1000',
            'payment_method' => '1',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_date' => [

                    ],
                ],
            ]);
    }

    public function testCreateCashOutPaymentDateWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023/08/25',
            'cash_out' => '1000',
            'payment_method' => '1',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_date' => [

                    ],
                ],
            ]);
    }

    public function testCreateCashOutCashOutEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-25',
            'cash_out' => '',
            'payment_method' => '1',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'cash_out' => [

                    ],
                ],
            ]);
    }

    public function testCreateCashOutCashOutIsString()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-25',
            'cash_out' => 'abc',
            'payment_method' => '1',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'cash_out' => [

                    ],
                ],
            ]);
    }

    public function testCreateCashOutPaymentMethodEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-25',
            'cash_out' => '1000',
            'payment_method' => '',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_method' => [

                    ],
                ],
            ]);
    }

    public function testCreateCashOutPaymentMethodWrongRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-25',
            'cash_out' => '1000',
            'payment_method' => '3',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_method' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'payment_date' => '2023-08-01',
        ])->assertStatus(Response::HTTP_OK);
    }

    public function testUpdateCashOutPaymentDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'payment_date' => '',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_date' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutPaymentDateWrongFormat()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'payment_date' => '2023-08',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_date' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutCashOutEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'cash_out' => '',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'cash_out' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutCashOutIsString()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'cash_out' => 'abc',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'cash_out' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutPaymentMethodEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'payment_method' => '',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_method' => [

                    ],
                ],
            ]);
    }

    public function testUpdateCashOutPaymentMethodWrongRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->json('put', 'api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, [
            'token' => $token,
            'payment_method' => '3',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'payment_method' => [

                    ],
                ],
            ]);
    }

    public function testCashOutDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $cashOut = CashOut::where('driver_id', $driver->id)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->get('api/driver/'.$driver->id.'/cash-out'.'/'.$cashOut->id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "id",
                    "driver_id",
                    "cash_out",
                    "payment_method",
                    "payment_date",
                    "note",
                    "status",
                    "created_at",
                    "updated_at",
                    "deleted_at"
                ]
            ]);
    }

    public function testCashOutDetailFail()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $response = $this->actingAs($user)->get('api/driver/'.$driver->id.'/cash-out/100000', ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }

    public function testCashOutDeleteSucess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();

        $newCashOut = $this->actingAs($user)->json('post', 'api/driver/'.$driver->id.'/cash-out', [
            'token' => $token,
            'payment_date' => '2023-08-30',
            'cash_out' => '500',
            'payment_method' => '1',
            'note' => NULL,
        ]);

        $response = $this->actingAs($user)->json('delete', 'api/driver/'.$driver->id.'/cash-out'.'/'.$newCashOut['data']['id'], ['token' => $token])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCashOutDeleteFail()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::where('type', 4)->orderBy('id', 'asc')->first();
        $id = 10000;

        $response = $this->actingAs($user)->json('delete', 'api/driver/'.$driver->id.'/cash-out'.'/'.$id, ['token' => $token])
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }
}
