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
                    'driver_code' => "0001",
                    'driver_name' => "tuan minh",
                    'start_date' => "2022-08-15",
                    'birth_day' => "2020-12-03",
                    'status' => "on",
                ]
            );
        }
        $course = Course::first();
        if (!$course) {
            $course = Course::factory()->create([
                    'flag' => "yes",
                    'course_code' => "0001",
                    'course_name' => "son test 1",
                    'start_time' => "06:00",
                    'end_time' => "09:15",
                    'break_time' => "1.50",
                    'start_date' => "2022-08-25",
                    'end_date' => "2022-08-29",
                    'status' => "on",
                    'note' => "son test 1",
                ]
            );
        }
        $driverCourse = DriverCourse::where(DriverCourse::DRIVER_COURSE_DRIVER_CODE, $driver->driver_code)
            ->where(DriverCourse::DRIVER_COURSE_COURSE_CODE, $course->course_code)->first();
        if (!$driverCourse) {
            $course = DriverCourse::create([
                    'driver_code' => $driver->driver_code,
                    'course_code' => $course->course_code,
                    'is_checked' => "no",
                ]
            );
        }

    }

    public function testDriverCourseListSuccess()
    {
        $user = User::where('user_code', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $id = $driver->id;
        $response = $this->actingAs($user)->get('api/driver-course/list/' . $id , ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(CODE_SUCCESS);
    }

    public function testDriverCourseUpdateCoursesSuccess()
    {
        $user = User::where('user_code', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $id = $driver->id;
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            'driver_id' => $id,
            'items' => [
                [
                    'course_code' => "00001",
                    'is_checked' => 'no'
                ]
            ]
        ])
        ->assertStatus(CODE_SUCCESS);
    }

    public function testDriverCourseUpdateCoursesFasleCode()
    {
        $user = User::where('user_code', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $id = $driver->id;
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            'driver_id' => $id,
            'items' => [
                [
                    'course_code' => "0000sdsdfsd1",
                    'is_checked' => 'no'
                ]
            ]
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDriverCourseUpdateCoursesCodeCheckedSuccess()
    {
        $user = User::where('user_code', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $id = $driver->id;
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            'driver_id' => $id,
            'items' => [
                [
                    'course_code' => "00001",
                    'is_checked' => 'yes'
                ]
            ]
        ])
        ->assertStatus(CODE_SUCCESS);
    }

    public function testDriverCourseUpdateCoursesCodeCheckedFail()
    {
        $user = User::where('user_code', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $driver = Driver::first();
        $id = $driver->id;
        $response = $this->actingAs($user)->json('post', 'api/driver-course', [
            'token' => $token,
            'driver_id' => $id + 1,
            'items' => [
                [
                    'course_code' => "00001",
                    'is_checked' => 'yes'
                ]
            ]
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
