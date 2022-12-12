<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CoursePattern;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CoursePatternTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $user = User::where('user_code', '=', '1122')->first();
        if (!$user) {
            User::CreateDriver('1122', 'Super Admin', 'abc12345678', 'admin');
        }
        $couseItems = Course::factory()->create([
                    'flag' =>  "no" ,
                    'course_code' =>  '0001',
                    'course_name' => "son test 1" ,
                    'start_time' => "06:00",
                    'end_time' => "09:15",
                    'break_time' => "1.50" ,
                    'start_date' =>  "2022-08-25",
                    'end_date' => "2022-08-29" ,
                    'status' => "off" ,
                    'note' => "son test 1",
                ]);
        $couseItems = Course::factory()->create([
                    'flag' =>  "no" ,
                    'course_code' =>  '0002',
                    'course_name' => "son test 1" ,
                    'start_time' => "06:00",
                    'end_time' => "09:15",
                    'break_time' => "1.50" ,
                    'start_date' =>  "2022-08-25",
                    'end_date' => "2022-08-29" ,
                    'status' => "off" ,
                    'note' => "son test 1",
                ]);
        $coursePattern = CoursePattern::first();
        if (!$coursePattern) {
            CoursePattern::factory()->create([
                'course_parent_code' => '0001',
                'course_child_code' => '0002',
                'status' => 'yes'
            ]);
            CoursePattern::factory()->create([
                'course_parent_code' => '0002',
                'course_child_code' => '0001',
                'status' => 'yes'
            ]);
            CoursePattern::factory()->create([
                'course_parent_code' => '0001',
                'course_child_code' => '0001',
                'status' => 'duplicate'
            ]);
            CoursePattern::factory()->create([
                'course_parent_code' => '0002',
                'course_child_code' => '0002',
                'status' => 'duplicate'
            ]);
        }
        $token = \JWTAuth::fromUser($user);
    }
    public function testCoursePatternListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course-pattern', [
            'token' => $token
        ])
        ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCoursePatternListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/course-pattern?token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_OK);
    }

    public function testCoursePatternEditSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $coursePattern = CoursePattern::first();
        $id = $coursePattern->id;
        $response = $this->actingAs($user)->json('put', 'api/course-pattern/' . $id, [
            'token' => $token,
            'status' => 'yes'
        ])->assertStatus(CODE_SUCCESS);

        $coursePattern = CoursePattern::first();
        $id = $coursePattern->id;

        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCoursePatternEditFalseId()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('put', 'api/course-pattern/' . $id, [
            'token' => $token,
            'status' => 'yes'
        ])->assertStatus(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testCoursePatternEditEmptyTokenParams()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $coursePattern = CoursePattern::first();
        $id = $coursePattern->id;
        $response = $this->actingAs($user)->json('put', 'api/course-pattern/' . $id, [
            // 'token' => $token,
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

    public function testCoursePatternEditEmptyStatusParams()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $id = 2;
        $response = $this->actingAs($user)->json('put', 'api/course-pattern/' . $id, [
            'token' => $token,
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCoursePatternEditFalseStatusParams()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $id = 2;
        $response = $this->actingAs($user)->json('put', 'api/course-pattern/' . $id, [
            'token' => $token,
            'status' => 'yest'
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCoursePatternDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $coursePattern = CoursePattern::first();
        $response = $this->actingAs($user)->json('get', 'api/course-pattern/' . $coursePattern->id, [
            'token' => $token
        ])
        ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCoursePatternDetailFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtolower(now());
        $response = $this->actingAs($user)->json('get', 'api/course-pattern/' . $id, [
            'token' => $token
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
