<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Str;

class DriverTest extends TestCase
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
            $driver = Driver::factory()->count(5)->create();
        }
    }

    public function testDriverListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/driver?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testDriverListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get("api/driver?field='nis'" . '&token=' . $token);
        $this->assertEquals($response->decodeResponseJson()['code'],\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByDriverNameSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=driver_name&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByDriverNameFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=driver_na&sortby=jkl' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByDriverCodeSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=driver_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByDriverCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=driver_code&sortby=mmm' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByDriverStatusSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=status&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByDriverStatusFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/driver?field=status&sortby=abc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testCanCreateDriverSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver?'. 'token=' . $token, [
            "type" => 1,
            "driver_code" => "00055",
            "driver_name" => "Bach",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateTypeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "driver_name" => "Test False",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'type' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCreateTypeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 5,
            "driver_name" => "Test False",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'type' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testCreateDriverCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "",
            "driver_code" => "",
            "car" => "LamBo",
            "start_date" => "",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateDriverCodeDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0001",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "anh duong anh em duong em",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateDriverCodeNotHaftWidth()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateDriverCodeGreaterThan15Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "000225acxczxzczxczxczxczxczxczxczxczxczxc",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "chuvitconchaylontonzxczxczxczxczxczxczxczxczxc",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateCarEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "start_date" => "2022-08-20",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'car' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameStartDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameStartDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "bicty",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameStartDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022/08/18",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameEndDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "end_date" => "zxczxczx",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameEndDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "end_date" => "2022/08/16",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameEndDateBeforeStartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNoteGreaterThan1000Characters()
    {
        $random = Str::random(1200);
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "note" => $random,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'note' => [
                    ],
                ],
            ]);
    }

    public function testCanUpdateDriverSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'type' => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCanUpdateDriverFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => "",
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateTypeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => "",
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'type' => [
                    ],
                ],
            ]);
    }

    public function testUpdateTypeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 5,
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'type' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinhzczxczxczxczxczxczxczxczxzxc",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverCodeDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0002",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverCodeGreaterThan15Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-zzxczxczxczxczxczxczxczxzxc",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_code' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameStartDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "abc-123-z",
            "car" => "LamBo",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameStartDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "bicty",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameStartDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022/08/18",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameEndDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "end_date" => "zxczxczx",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameEndDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-20",
            "end_date" => "2022/08/16",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameEndDateBeforeStartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "note" => "toi the toi chang con tin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNoteGreaterThan1000Characters()
    {
        $random = Str::random(1200);
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            "type" => 1,
            "driver_name" => "TuanMinh",
            "driver_code" => "0005",
            "car" => "LamBo",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "note" => $random,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'note' => [
                    ],
                ],
            ]);
    }

    public function testDriverDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->get('api/driver/' . $driver->id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "code",
                "data" => [
                    "id",
                    "type",
                    "driver_code",
                    "driver_name",
                    "start_date",
                    "end_date",
                    "car",
                    "note",
                    "status",
                    "created_at",
                    "updated_at",
                    "deleted_at"
                ],
            ]);
    }

    public function testDriverDetailFalseNotFound()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->get('api/driver/' . $id . "?token=".$token)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }

    public function testDriverDeleteSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->delete('api/driver/' . $driver->id . "?token=".$token)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "code",
                "message"=> [
                    "code",
                    "status",
                    "message",
                ],
            ]);
    }

    public function testDriverDeleteFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->delete('api/driver/' . 9 . "?token=".$token)
            ->assertStatus(Response::HTTP_OK);
        $this->assertEquals(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, $response->decodeResponseJson()['code']);
    }

}
