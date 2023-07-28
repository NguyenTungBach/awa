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

class DriverCourseDeleteTest extends TestCase
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

    }

    /////////Delete Start//////////
    public function testDriverCourseDeleteSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer ". $token)
            ->actingAs($user)->json('delete', "api/driver-course/1")
            ->assertStatus(CODE_SUCCESS)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "id",
                    "driver_id",
                    "course_id",
                    "start_time",
                    "end_time",
                    "break_time",
                    "date",
                    "status",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                ],
            ]);
    }

    public function testDriverCourseDetailNotFound_id()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $driver_course = DriverCourse::find(1);
        $response = $this->withHeader('Authorization', "Bearer ". $token)
            ->actingAs($user)->json('delete', "api/driver-course/99")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans("errors.not_found"),
                "message_content" => null,
                "message_internal" => null,
                "data_error" => null
            ]);
    }
    /////////Delete End//////////
}
