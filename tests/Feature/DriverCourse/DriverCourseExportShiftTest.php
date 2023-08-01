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
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class DriverCourseExportShiftTest extends TestCase
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

    /////////Export Shift Start//////////
    public function testDriverCourseExportShift()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)
            ->withHeader('Authorization', "Bearer ". $token)
            ->post('/api/calendar/setup-data?targetyyyy=2023');
        Excel::fake();
        $result = $this->actingAs($user)->withHeader('Authorization', "Bearer ". $token)
            ->json('get', 'api/driver-course/export-shift', [
            'field' => "drivers.type",
            'sortby' => "desc",
            'month_year' => "2023-08",
            'closing_date' => "25",
        ])->assertStatus(\Illuminate\Http\Response::HTTP_OK);
    }

    public function testDriverCourseExportShiftExpressCharge()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->actingAs($user)
            ->withHeader('Authorization', "Bearer ". $token)
            ->post('/api/calendar/setup-data?targetyyyy=2023');
        Excel::fake();
        $result = $this->actingAs($user)->withHeader('Authorization', "Bearer ". $token)
            ->json('get', 'api/driver-course/export-shift-express-charge', [
                'field' => "drivers.type",
                'sortby' => "desc",
                'month_year' => "2023-08",
            ])->assertStatus(\Illuminate\Http\Response::HTTP_OK);
    }

    /////////Export Shift End//////////
}
