<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DayOff;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DayOffTest extends TestCase
{
    protected $user;

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
            $user = User::factory()->create([
                "user_name" => "Super Admin",
                "user_code" => "1122",
                "password" => "abc12345678",
                "role" => "admin"

            ]);
        }
        $driver = Driver::first();
        if (!$driver) {
            $driver = Driver::factory()->create([
                'flag' => "",
                "driver_code" => "9860",
                "driver_name" => "TuanMinh",
                "start_date" => "2022-08-20",
                "end_date" => "",
                "birth_day" => "2020-12-03",
                "working_day" => "1",
                "day_of_week" => "mon",
                "status" => "on",
                "working_time" => "",
                "note" => "thoi roi ta da xa nhau",
            ]);
        }
    }

    public function testDayOffListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/day-off?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testDayOffListWithDateSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/day-off?data=2022-09&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testDayOffListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get("api/day-off?date='2088-09-09'" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanUpdateDayOffSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => $date,
                    "type" => "D-4",
                ]
            ],
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCanUpdateCourseCodeForDayOffSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::with('driverCourse', 'course')->first();
        if (!$driver) {
            // create a driver
        }
        if (!count($driver->driverCourse)) {
            // add a course code
            $course = Course::first();
            if (!$course) {
                // create a course
                // $course['course_code'] = '9999';
            }
            DriverCourse::create([
                'driver_code' => $driver->driver_code,
                'course_code' => $course->course_code,
                'is_checked' => 'no'
            ]);
            $driver->refresh();

        }

        $driveCourses = $driver->driverCourse;

        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => $date,
                    "type" => "D-5",
                    "has_codes" => $driveCourses[0]->course_code
                ]
            ],
        ])->assertStatus(CODE_SUCCESS)
        ->assertJsonStructure([
            "code",
            "data" => [
                0 => [
                    "id",
                    "driver_code",
                    "driver_name",
                    "date",
                    "type",
                    "has_codes",
                    "color"
                ]
            ]
        ])
        ;
    }
    public function testCanUpdateCourseCodeNullForDayOffSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::with('driverCourse', 'course')->first();
        if (!$driver) {
            // create a driver
        }
        if (!count($driver->driverCourse)) {
            // add a course code
            $course = Course::first();
            if (!$course) {
                // create a course
                // $course['course_code'] = '9999';
            }
            DriverCourse::create([
                'driver_code' => $driver->driver_code,
                'course_code' => $course->course_code,
                'is_checked' => 'no'
            ]);
            $driver->refresh();

        }

        $driveCourses = $driver->driverCourse;

        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => $date,
                    "type" => "D-5",
                    "has_codes" => ''
                ]
            ],
        ])->assertStatus(CODE_SUCCESS)
        ->assertJsonStructure([
            "code",
            "data" => []
        ])
        ;
    }

    public function testUpdateDayOffDriverCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => '',
                    "date_off" => $date,
                    "type" => "D-4",
                ]
            ],

        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

    public function testUpdateDayOffDriverCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => '4575445454',
                    "date_off" => $date,
                    "type" => "D-4",
                ]
            ],

        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateDayOffDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => '',
                    "type" => "D-4",
                ]
            ],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

    public function testUpdateDayOffNotFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => '456546456',
                    "type" => "D-4",
                ]
            ],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

    public function testUpdateDayOffTypeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => $date,
                    "type" => "",
                ]
            ],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

    public function testUpdateDayOffTypeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $date = Carbon::now()->addDay(5)->toDateString();
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $response = $this->actingAs($user)->json('post', 'api/day-off', [
            'token' => $token,
            'date' => Carbon::now()->format('Y-m'),
            'day_off' => [
                [
                    "driver_code" => $driver->driver_code,
                    "date_off" => $date,
                    "type" => "D-9",
                ]
            ],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

}
