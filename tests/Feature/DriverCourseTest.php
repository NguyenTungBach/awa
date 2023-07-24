<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DriverCourseTest extends TestCase
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

//        $driverCourse = DriverCourse::where(DriverCourse::DRIVER_COURSE_DRIVER_CODE, $driver->driver_code)
//            ->where(DriverCourse::DRIVER_COURSE_COURSE_CODE, $course->course_code)->first();
//        if (!$driverCourse) {
//            $course = DriverCourse::create([
//                    'driver_code' => $driver->driver_code,
//                    'course_code' => $course->course_code,
//                    'is_checked' => "no",
//                ]
//            );
//        }

    }

//    public function testDriverCourseListSuccess()
//    {
//        $user = User::where('user_code', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $driver = Driver::first();
//        $id = $driver->id;
//        $response = $this->actingAs($user)->get('api/driver-course/list/' . $id , ['HTTP_Authorization' => 'Bearer' . $token])
//            ->assertStatus(CODE_SUCCESS);
//    }

    public function testDriverCourseCreateSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(CODE_SUCCESS)
            ->assertJsonStructure([
                "code",
                "data" => [
                    "driver_id",
                    'items' => [
                        [
                            "course_id",
                            "date",
                            "start_time",
                            "break_time",
                            "end_time",
                        ]
                    ],
                    "status"
                ],
            ]);
    }

    public function testDriverCourseCreateItemEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateDrive_idEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateDrive_idNotFound()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'driver_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateCourse_idEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.course_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateCourse_idNotFound()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 9,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.course_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 9,
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 4,
                    "date" => "2023/07/24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateStart_timeNotFound()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.start_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateStart_timeNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "start_time" => "08:00:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.start_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateStart_timeWrongTimeRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "start_time" => "08:11",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.start_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateEnd_timeNotFound()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "break_time" => "12:00",
                    "start_time" => "08:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.end_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateEnd_timeNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00:00",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.end_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateEnd_timeWrongTimeRule()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 9,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => "2023/07/24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:11",
                ],
                [
                    "course_id" => 2,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.end_time' => [

                    ],
                ],
                "data_error"
            ]);
    }
//    public function testDriverCourseUpdateCoursesFasleCode()
//    {
//        $user = User::where('user_code', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $driver = Driver::first();
//        $id = $driver->id;
//        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
//            'token' => $token,
//            'driver_id' => $id,
//            'items' => [
//                [
//                    'course_code' => "0000sdsdfsd1",
//                    'is_checked' => 'no'
//                ]
//            ]
//        ])
//        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
//    }
//
//    public function testDriverCourseUpdateCoursesCodeCheckedSuccess()
//    {
//        $user = User::where('user_code', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $driver = Driver::first();
//        $id = $driver->id;
//        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
//            'token' => $token,
//            'driver_id' => $id,
//            'items' => [
//                [
//                    'course_code' => "00001",
//                    'is_checked' => 'yes'
//                ]
//            ]
//        ])
//        ->assertStatus(CODE_SUCCESS);
//    }
//
//    public function testDriverCourseUpdateCoursesCodeCheckedFail()
//    {
//        $user = User::where('user_code', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $driver = Driver::first();
//        $id = $driver->id;
//        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
//            'token' => $token,
//            'driver_id' => $id + 1,
//            'items' => [
//                [
//                    'course_code' => "00001",
//                    'is_checked' => 'yes'
//                ]
//            ]
//        ])
//        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
//    }

}
