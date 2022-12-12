<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Str;

class CourseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=UserSeeder');
        $this->artisan('db:seed --class=CourseSeeder');
        $user = User::where('user_code', '=', '1122')->first();
        if (!$user) {
            User::createUser('1122', 'Super Admin', 'abc12345678', 'admin');
        }
        $course = Course::first();
        if (!$course) {
            $course = Course::factory()->count(5)->create();
        }
    }

    public function testCourseListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/course?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCourseListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get("api/course?field='nis'" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByCourseCodeSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course?field=course_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByCourseNameSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course?field=course_name&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByCourseCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course?field=course_code&sortby=abc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByCourseNameFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course?field=course_name&sortby=def' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateCourseSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course/', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "00050",
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'point' => 10,
            'status' => "on" ,
            'note' => "son test 9",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateCourseFail()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('post', 'api/course/', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "00050",
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanCreateCourseCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "",
            'course_name' => "son test 9" ,
            'start_time' => "06:00",
            'end_time' => "16:15",
            'break_time' => "1.00" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal" => [
                'course_code' => [

                ],
            ],
        ]);
    }

    public function testCanCreateCourseCodeDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' => "",
            "course_code" => "0005",
            "course_name" => "TuanMinh",
            'start_time' => "06:00",
            'end_time' => "16:15",
            'break_time' => "1.00" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'course_code' => [

                    ],
                ],
            ]);
    }

    public function testCanCreateCourseCodeNotNumeric()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "abc1291",
            'course_name' => "son test 9" ,
            'start_time' => "06:00",
            'end_time' => "16:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal" => [
                'course_code' => [

                ],
            ],
        ]);
    }

    public function testCanCreateCourseNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "abc1291",
            'course_name' => "" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal" => [
                'course_name' => [

                ],
            ],
        ]);
    }

    public function testCanCreateCourseCode6Charapter()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "010239",
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal" => [
                'course_code' => [

                ],
            ],
        ]);
    }

    public function testCanCreateCourseName31Charapter()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01028",
            'course_name' => "son test 6 charapter ad ưoasea1" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal" => [
                'course_name' => [

                ],
            ],
        ]);
    }

    public function testCanCreateCourseStartDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01027",
            'course_name' => "son test 6" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "abc13132",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanCreateCourseStartDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01026",
            'course_name' => "son test 6" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022/08/29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanCreateCourseEndDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01025",
            'course_name' => "son test 6" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "adadadadd" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanCreateCourseEndDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01024",
            'course_name' => "son test 6" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022/08/30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanCreateCourseEndDateBeforeStartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01020",
            'course_name' => "son test 6" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-19" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanCreateCourseNote1000Char()
    {
        $random = Str::random(1200);
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/course', [
            'token' => $token,
            'flag' =>  "no" ,
            'course_code' =>  "01023",
            'course_name' => "son test 6 " ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => $random,
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

    public function testUpdateCourseSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'point' => 11,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCanUpdateCourseFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('put', 'api/course' . $id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal",
            "data_error",
        ]);
    }

    public function testCanUpdateCourseFlagFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "mini" ,
            'course_name' => "son test 6 charapter" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "" ,
            'start_time' => "06:00",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'course_name' => [
                    ],
                ],
            ]);
    }

    public function testCanUpdateCourseStartTimeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "",
            'end_time' => "15:15",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'start_time' => [
                    ],
                ],
            ]);
    }

    public function testCanUpdateCourseEndTimeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'end_time' => [
                    ],
                ],
            ]);
    }

    public function testCanUpdateCourseBreakTimeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "" ,
            'start_date' =>  "2022-08-29",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'break_time' => [
                    ],
                ],
            ]);
    }

    public function testCanUpdateCourseStartDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseEndDateBeforeStartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-30",
            'end_date' => "2022-08-19" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseStartDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "ddđaaaaaa",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseStartDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "2022/08/19",
            'end_date' => "2022-08-30" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseEndDateNoFomartDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-19",
            'end_date' => "aaaaaaaaaaaaaaa" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseEndDateNoMalformed()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-30",
            'end_date' => "2022/08/19" ,
            'status' => "off" ,
            'note' => "son test 9",
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

    public function testCanUpdateCourseNote1200Char()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $random = Str::random(1200);
        $response = $this->actingAs($user)->json('put', 'api/course/' . $course->id, [
            'token' => $token,
            'flag' =>  "no" ,
            'course_name' => "son tes1" ,
            'start_time' => "06:00",
            'end_time' => "15:00",
            'break_time' => "1.25" ,
            'start_date' =>  "2022-08-30",
            'end_date' => "2022/08/19" ,
            'status' => "off" ,
            'note' => $random,
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

    public function testDeleteCourseSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();
        $response = $this->actingAs($user)->json('delete', 'api/course/' . $course->id, [
            'token' => $token,
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCourseDeleteFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('delete', 'api/course/' . $id, ['token' => $token])
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }

    public function testDetailCourseSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $course = Course::first();

        $response = $this->actingAs($user)->json('get', 'api/course/' . $course->id, [
            'token' => $token,
        ])->assertStatus(CODE_SUCCESS)
        ->assertJsonStructure([
            "code",
            "data" => [
                'owner' => []
            ],
        ]);
    }

    public function testCanDetailCourseFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('put', 'api/course' . $id, [
            'token' => $token,
        ])->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
        ->assertJsonStructure([
            "code",
            "message",
            "message_content",
            "message_internal",
            "data_error",
        ]);
    }
}
