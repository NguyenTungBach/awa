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
        $response = $this->actingAs($user)->get("api/driver?field='nis'" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateDriverFlagFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "mini",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "ke tu khi phao do ruou hong",
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

    public function testCreateDriverCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "ke tu khi phao do ruou hong",
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
            'flag' => "",
            "driver_code" => "0001",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
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

    public function testCreateDriverCodeNotNumeric()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "kk05",
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

    public function testCreateDriverCodeMoreThan1Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "",
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

    public function testCreateDriverCodeGreaterThan4Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "000225",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "",
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
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNameGreaterThan10Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "chuvitconchaylonton",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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

    public function testCreateDriverNameStartDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "bigcyti",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022/08/18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "dfgdfgdfg",
            "birth_day" => "2022-08/16",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "2022/08/16",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "birth_day" => "2022-08/16",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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

    public function testCreateDriverBirthdayEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverBirthdayNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "45345FG",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverBirthDayNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022/08/18",
            "end_date" => "",
            "birth_day" => "2020/12/03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverWorkingDayEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_day' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverWorkingDayFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "7",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_day' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverDayOfWeekFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "pi,ka,chu",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'day_of_week' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverWorkingTime()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "",
            "working_time" => "lalalal",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_time' => [
                    ],
                ],
            ]);
    }

    public function testCreateDriverNote1000Char()
    {
        $random = Str::random(1200);
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver', [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "",
            "working_time" => "",
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
            'flag' => "",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCanUpdateDriverFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $id, [
            'token' => $token,
            'flag' => "",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "thoi roi ta da xa nhau",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateDriverFlagFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "mini",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "mon,tue,wed",
            "working_time" => "",
            "note" => "ke tu khi phao do ruou hong",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'flag' => [
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "",
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
                    'driver_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNameGreaterThan10Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "chuvitconchaylonton",
            "start_date" => "2022-08-20",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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

    public function testUpdateDriverNameStartDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "bigcyti",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022/08/18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "dfgdfgdfg",
            "birth_day" => "2022-08/16",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "2022/08/16",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "2022-08-10",
            "birth_day" => "2022-08/16",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
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

    public function testUpdateDriverBirthdayEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverBirthdayNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "45345FG",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverBirthDayNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022/08/18",
            "end_date" => "",
            "birth_day" => "2020/12/03",
            "working_day" => "1",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'birth_day' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverWorkingDayEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_day' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverWorkingDayFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "7",
            "day_of_week" => "admin",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_day' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverDayOfWeekFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "pi,ka,chu",
            "working_time" => "admin",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'day_of_week' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverWorkingTime()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "",
            "working_time" => "lalalal",
            "note" => "admin",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'working_time' => [
                    ],
                ],
            ]);
    }

    public function testUpdateDriverNote1000Char()
    {
        $random = Str::random(1200);
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('put', 'api/driver/' . $driver->id, [
            'token' => $token,
            'flag' => "",
            "driver_code" => "0005",
            "driver_name" => "TuanMinh",
            "start_date" => "2022-08-18",
            "end_date" => "",
            "birth_day" => "2020-12-03",
            "working_day" => "1",
            "day_of_week" => "",
            "working_time" => "",
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
                    "flag",
                    "driver_code",
                    "driver_name",
                    "start_date",
                    "end_date",
                    "birth_day",
                    "note",
                    "working_day",
                    "day_of_week",
                    "working_time",
                    "status",
                    "created_at",
                    "updated_at",
                    "deleted_at"
                ],
            ]);
    }

    public function testDriverDetailFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->get('api/driver/' . $id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
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
        $timeDelete = Carbon::now()->subMonths(2);
        $countListDriver = Driver::whereYear(Driver::DRIVER_END_DATE, $timeDelete->year)
            ->whereMonth(Driver::DRIVER_END_DATE, $timeDelete->month)
            ->count();
        $this->assertEquals($countListDriver, 0);
    }

    public function testDriverDeleteFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $timeDelete = Carbon::now()->subMonths(2);
        $countListDriver = Driver::whereYear(Driver::DRIVER_END_DATE, $timeDelete->year)
            ->whereMonth(Driver::DRIVER_END_DATE, $timeDelete->month)
            ->count();

        $this->assertFalse($countListDriver > 0);
    }

}
