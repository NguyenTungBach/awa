<?php

namespace Tests\Feature\DriverCourse;

use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DriverCourseCreateTest extends TestCase
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

    public function testDriverCourseListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)->json('get', 'api/driver-course', [
            'token' => $token,
            'month_year' => "2023-07",
        ])->assertStatus(CODE_SUCCESS);
    }

    /////////Create Start//////////
    public function testDriverCourseCreateSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $course = Course::find(1);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => $course->ship_date,
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

    public function testDriverCourseCreateEnd_timeWrongAfter_or_equalStart_time()
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
                    "end_time" => "07:00",
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

    public function testDriverCourseCreateBreak_timeNotFound()
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
                    'items.0.break_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateBreak_timeNoFomartDate()
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
                    "break_time" => "12:00:00",
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
                    'items.0.break_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseCreateBreak_timeWrongTimeRule()
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
                    "break_time" => "12:11",
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
                    'items.0.break_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    // 1. Duplicate DriverCourseCreate
    public function testDriverCourseCreateDuplicateItem()
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
                    "break_time" => "07:00",
                    "end_time" => "09:00",
                ],
                [
                    "course_id" => 1,
                    "date" => "2023-07-24",
                    "start_time" => "08:00",
                    "break_time" => "12:00",
                    "end_time" => "18:00",
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('errors.duplicate_course_id_and_date',[
                    "course_id"=> 1,
                    "date"=> "2023-07-24"
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 2. In Final_closing_histories DriverCourseCreate
    public function testDriverCourseCreateInFinal_closing_histories()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $course = Course::find(1);

        $testFinalClosingHistories = FinalClosingHistories::create([
            'date' => $course->ship_date,
            'month_year' => Carbon::parse($course->ship_date)->format("Y-m"),
            'type' => 1,
            'status' => 1,
        ]);
//        dd($testFinalClosingHistories);
//        DB::table('final_closing_histories')->where('id', $testFinalClosingHistories->id)->delete();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => $course->ship_date,
                    "start_time" => "08:00",
                    "break_time" => "07:00",
                    "end_time" => "09:00",
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.final_closing_histories" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course->id, course_name: $course->course_name , and date: $course->ship_date"
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 3. DriverRetirement DriverCourseCreate
    public function testDriverCourseCreateDriverRetirement()
    {
        DB::table('final_closing_histories')->truncate();
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $course = Course::find(1);
        $driver->end_date = $course->ship_date;
        $driver->save();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => $course->ship_date,
                    "start_time" => "08:00",
                    "break_time" => "07:00",
                    "end_time" => "09:00",
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.end_date_retirement" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course->id, course_name: $course->course_name",
                    "end_date"=> Carbon::parse($driver->end_date)->format('Y-m-d')
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 4. In NotInShip_dateCourses DriverCourseCreate
    public function testDriverCourseCreateNotInShip_dateCourses()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $course = Course::find(1);
        $fake_date = Carbon::parse($course->ship_date)->subDay()->format("Y-m-d");
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => $fake_date,
                    "start_time" => "08:00",
                    "break_time" => "07:00",
                    "end_time" => "09:00",
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.unlike_ship_date" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course->id, course_name: $course->course_name, and date: $fake_date",
                    "ship_date"=> $course->ship_date
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 5. CourseExistDriver DriverCourseCreate
    public function testDriverCourseCreateCourseExistDriver()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $course = Course::find(1);
        $token = \JWTAuth::fromUser($user);

        $driver_course = new DriverCourse();
        $driver_course->driver_id = $driver->id;
        $driver_course->course_id = $course->id;
        $driver_course->date = $course->ship_date;
        $driver_course->start_time = $course->start_date;
        $driver_course->end_time = $course->end_date;
        $driver_course->break_time = $course->break_time;
        $driver_course->status = 1;
        $driver_course->save();

        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            "driver_id" => 1,
            "items" => [
                [
                    "course_id" => 1,
                    "date" => $course->ship_date,
                    "start_time" => "08:00",
                    "break_time" => "07:00",
                    "end_time" => "09:00",
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.has_been_assigned" ,[
                    "attribute"=> "driver_id: $driver_course->driver_id, driver_name: $driver->driver_name, course_id: $driver_course->course_id, course_name: $course->course_name and date: $course->ship_date"
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }
    /////////Create End//////////
}
