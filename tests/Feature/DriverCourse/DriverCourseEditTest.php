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

class DriverCourseEditTest extends TestCase
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

    /////////Edit Start//////////
    public function testDriverCourseEditSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => 1,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ],
                        [
                            "course_id" => $course7->id,
                            "date" => $course7->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(CODE_SUCCESS)
            ->assertExactJson([
                "code"=> CODE_SUCCESS,
                "data"=> [
                    "month_year" => Carbon::now()->format('Y-m'),
                    "items" => [
                        [
                            "driver_id" => 1,
                            "listShift" => [
                                [
                                    "course_id" => $course6->id,
                                    "date" => $course6->ship_date,
                                    "start_time" => "08:00",
                                    "break_time" => "12:00",
                                    "end_time" => "18:00",
                                ],
                                [
                                    "course_id" => $course7->id,
                                    "date" => $course7->ship_date,
                                    "start_time" => "08:00",
                                    "break_time" => "12:00",
                                    "end_time" => "18:00",
                                ]
                            ]
                        ],
                    ]
                ]
            ]);
    }

    public function testDriverCourseEditMonthYearEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
        ->json('post', 'api/driver-course/update-course',[
            "items" => [
                [
                    "driver_id" => 1,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
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

    public function testDriverCourseEditItemsEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
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

    public function testDriverCourseEditDriver_idEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "date" => $course6->ship_date,
                                "start_time" => "08:00",
                                "break_time" => "12:00",
                                "end_time" => "18:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.driver_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseEditCourse_idEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => 1,
                        "listShift" => [
                            [
                                "date" => $course6->ship_date,
                                "start_time" => "08:00",
                                "break_time" => "12:00",
                                "end_time" => "18:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.listShift.0.course_id' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseEditDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => 1,
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "start_time" => "08:00",
                                "break_time" => "12:00",
                                "end_time" => "18:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.listShift.0.date' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseEditStart_timeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => 1,
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "date" => $course6->ship_date,
                                "break_time" => "12:00",
                                "end_time" => "18:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.listShift.0.start_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseEditBreak_timeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => 1,
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "date" => $course6->ship_date,
                                "start_time" => "08:00",
                                "end_time" => "18:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.listShift.0.break_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    public function testDriverCourseEditEnd_timeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)
            ->json('post', 'api/driver-course/update-course',[
                "month_year" => Carbon::now()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => 1,
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "date" => $course6->ship_date,
                                "start_time" => "08:00",
                                "break_time" => "12:00",
                            ]
                        ]
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'items.0.listShift.0.end_time' => [

                    ],
                ],
                "data_error"
            ]);
    }

    // 1.0. Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó
    public function testDriverCourseDriverWithSpecialCourseIdMustOneInDateEditSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $dayForCourse7 = Carbon::now()->addDay()->format("Y-m-d");
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
        $courseSpecial = Course::find(1);
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => $driver->id,
                    "listShift" => [
                        [
                            "course_id" => $courseSpecial->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ],
                        [
                            "course_id" => $course7->id,
                            "date" => $course7->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('errors.all_id_special_must_one',[
                    "driver_id"=> $driver->id,
                    "driver_name"=> $driver->driver_name,
                    "course_id"=> $courseSpecial->id,
                    "course_name"=> $courseSpecial->course_name,
                    "date"=> $course6->ship_date,
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 1.1. Duplicate driver_id DriverCourseCreate
    public function testDriverCourseEditDriver_idDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => $driver->id,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ],
                    ]
                ],
                [
                    "driver_id" => $driver->id,
                    "listShift" => [
                        [
                            "course_id" => $course7->id,
                            "date" => $course7->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('errors.duplicate_driver_id',[
                    "driver_id"=> $driver->id,
                    "driver_name"=> $driver->driver_name,
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 1.2. Duplicate driver_id And course_id DriverCourseCreate
    public function testDriverCourseEditDuplicateDriver_idAndCourse_id()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver1 = Driver::find(1);
        $driver2 = Driver::find(2);
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

        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => $driver1->id,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ],
                    ]
                ],
                [
                    "driver_id" => $driver2->id,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('errors.duplicate_driver_id_and_course_id',[
                    "driver_id"=> $driver2->id,
                    "course_id"=> $course6->id
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 1.3. Duplicate course_id và date DriverCourseCreate
    public function testDriverCourseEditNotInMonthYear()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
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

        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)
            ->actingAs($user)->json('post', 'api/driver-course/update-course', [
                "month_year" => Carbon::now()->addMonth()->format('Y-m'),
                "items" => [
                    [
                        "driver_id" => $driver->id,
                        "listShift" => [
                            [
                                "course_id" => $course6->id,
                                "date" => $course6->ship_date,
                                "start_time" => "08:00",
                                "break_time" => "12:00",
                                "end_time" => "18:00",
                            ],
                        ]
                    ],
                ]
            ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('errors.date_not_in_month',[
                    "driver_id"=> $driver->id,
                    "driver_name"=> $driver->driver_name,
                    "course_id"=> $course6->id,
                    "course_name"=> $course6->course_name,
                    "month_year"=> Carbon::now()->addMonth()->format('Y-m')
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 2. In Final_closing_histories DriverCourseCreate
    public function testDriverCourseEditInFinal_closing_histories()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);

        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");

//        dd($testFinalClosingHistories);
//        DB::table('final_closing_histories')->where('id', $testFinalClosingHistories->id)->delete();
        $token = \JWTAuth::fromUser($user);
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

        $testFinalClosingHistories = FinalClosingHistories::create([
            'date' => $dayForCourse6,
            'month_year' => Carbon::parse($dayForCourse6)->format("Y-m"),
            'type' => 1,
            'status' => 1,
        ]);
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => 1,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.final_closing_histories" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course6->id, course_name: $course6->course_name, and date: $course6->ship_date"
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 3. DriverRetirement DriverCourseCreate
    public function testDriverCourseEditDriverRetirement()
    {
        DB::table('final_closing_histories')->truncate();
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $token = \JWTAuth::fromUser($user);
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
        $driver->end_date = $course6->ship_date;
        $driver->save();
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => 1,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $course6->ship_date,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.end_date_retirement" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course6->id, course_name: $course6->course_name",
                    "end_date"=> $course6->ship_date
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    // 4. Day Must One With Special Id DriverCourseCreate
    public function testDriverCourseEditDateMustSameCourse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $driver = Driver::find(1);
        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
        $dayForCourse7 = Carbon::now()->addDays(2)->format("Y-m-d");
        $token = \JWTAuth::fromUser($user);
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
        $token = \JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', "Bearer ". $token)->actingAs($user)->json('post', 'api/driver-course/update-course', [
            "month_year" => Carbon::now()->format('Y-m'),
            "items" => [
                [
                    "driver_id" => $driver->id,
                    "listShift" => [
                        [
                            "course_id" => $course6->id,
                            "date" => $dayForCourse7,
                            "start_time" => "08:00",
                            "break_time" => "12:00",
                            "end_time" => "18:00",
                        ]
                    ]
                ],
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.unlike_ship_date" ,[
                    "attribute"=> "driver_id: $driver->id, driver_name: $driver->driver_name, course_id: $course6->id, course_name: $course6->course_name, and date: $dayForCourse7",
                    "ship_date"=> $course6->ship_date
                ]),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }

    /////////Edit End//////////
}
