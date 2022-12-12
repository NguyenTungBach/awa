<?php

namespace Tests\Feature;

use App\Exports\CourseScheduleExport;
use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CourseScheduleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // $this->artisan('db:seed');
        $user = User::where('user_code', '=', '1122')->first();
        if (!$user) {
            User::CreateDriver('1122', 'Super Admin', 'abc12345678', 'admin');
        }
        $course = Course::where(function ($query) {
                    $query->where('flag', Course::COURSE_FLAG_NO)
                            ->orWhereRaw('LENGTH(flag) = 0');
                })
                ->where('status', Course::COURSE_STATUS_WORK)
                ->where('course_code', 'axNc')
                ->first();

        if (!$course) {
            Course::create([
                'flag' => '',
                'course_code' => 'axNc',
                'course_name' => 'test',
                'start_date' => date('Y-m-d'),
                'start_time' => '6:00',
                'end_time' => '21:00',
                Course::COURSE_BREAK_TIME => '1.5',
                Course::COURSE_POINT => '1',
                'end_date' => '',
                'status' => 'on'
            ]);
        }
        $course = Course::where(function ($query) {
                    $query->where('flag', Course::COURSE_FLAG_NO)
                            ->orWhereRaw('LENGTH(flag) = 0');
                })
                ->with('courseSchedules')
                ->where('status', Course::COURSE_STATUS_WORK)
                ->where('course_code', 'axNc')
                ->first();
        if (!CourseSchedule::first()){
            $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
            $firstDay = \Carbon\Carbon::now()->firstOfMonth()->toDateString();

            foreach (range(0, $daysInMonth - 1) as $d) {
                $date = date('Y-m-d', strtotime($firstDay . ' + ' . $d . ' days'));
                $lunarJps = Calendar::pluck('week', 'date')->all();
                CourseSchedule::create([
                    'course_code' => 'axNc',
                    'course_name' => 'Maryse Auer',
                    'schedule_date' => $date,
                    'status' => 'on',
                    'lunar_jp' => 'a'//$lunar_jps[$date],
                ]);
            }

        }

    }
    public function testCourseScheduleListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', '/api/course-schedule', [
            'token' => $token
        ])
        ->assertStatus(CODE_SUCCESS);
    }
    public function testCourseScheduleListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/course-schedule')
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

    public function testCourseScheduleListParamsFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course-schedule', [

        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

    public function testCourseScheduleListSortByParamsSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course-schedule', [
            'token' => $token,
            'sorttype_id' => 'desc',
            'sorttype_group' => 'asc'
        ])
        ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }


    public function testCourseScheduleListViewMonthParamsSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course-schedule', [
            'token' => $token,
            'view_date' => '2022-09'
        ])
        ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }


    public function testCourseScheduleListViewMonthParamsFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/course-schedule', [
            'token' => $token,
            'view_date' => '2022-0'
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCourseScheduleEditManySuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);

        $response = $this->actingAs($user)->json('post', 'api/course-schedule/updates', [
            'token' => $token,
            'items' => [
                [
                    'id' => 1,
                    'status' => 'off'
                ],[
                    'id' => 2,
                    'status' => 'off'
                ],
                [
                    'course_id' => 2,
                    'status' => 'off',
                    'schedule_date' => '2022-10-02'
                ],

            ]
        ])
        ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCourseScheduleEditManyParamsFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);

        $response = $this->actingAs($user)->json('post', 'api/course-schedule/updates', [
            'token' => $token,
            'items' => [
                [
                    'ids' => 1,
                    'statuss' => 'off'
                ],[
                    'id' => 2,
                    'status' => 'off'
                ]
            ]
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCourseScheduleEditManyValidateFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);

        $response = $this->actingAs($user)->json('post', 'api/course-schedule/updates', [
            'token' => $token,
            'items' => [
                [
                    'id' => "dd",
                    'status' => 'ddd'
                ],[
                    'id' => 2,
                    'status' => 'off'
                ]
            ]
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCourseScheduleExport()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        Excel::fake();

        $res = $this->actingAs($user)
             ->json('get', 'api/course-schedule/export-data', [
                 'token' => $token,
                 'sortby_id' => 'asc',
                 'sortby_group' => 'asc',
             ])->assertStatus(\Illuminate\Http\Response::HTTP_OK);
    }

    public function testImportFileSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $contents = Storage::get('CourseSchedule/' . strtotime(date('Y-m')) . '/asc_asc/配車表_{' . date('Y') . '_' . date('m') . '}.xlsx');

        $file = UploadedFile::fake()->createWithContent('配車表_{' . date('Y') . '_' . date('m') . '}.xlsx', $contents);

        Excel::fake();


        $res = $this->actingAs($user)->json('post', 'api/course-schedule/import', [
            'token' => $token,
            'file' => $file
        ])->assertStatus(\Illuminate\Http\Response::HTTP_OK);

    }

    public function testImportFileFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $contents = Storage::get('CourseSchedule/' . strtotime(date('Y-m')) . '/asc_asc/配車表_{' . date('Y') . '_' . date('m') . '}.xlsx');

        $file = UploadedFile::fake()->createWithContent('配車表_{' . date('Y') . '_' . date('m') . '}.xlsx', $contents);

        Excel::fake();


        $res = $this->actingAs($user)->json('post', 'api/course-schedule/import', [
            'token' => $token,
            // 'file' => $file
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function testImportFileForDateFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $contents = Storage::get('CourseSchedule/' . strtotime(date('Y-m')) . '/asc_asc/配車表_{' . date('Y') . '_' . date('m') . '}.xlsx');

        $file = UploadedFile::fake()->createWithContent('配車表_{' . date('Y') . '_' . date('m') . '}.xlsx', $contents);

        Excel::fake();


        $res = $this->actingAs($user)->json('post', 'api/course-schedule/import', [
            'token' => $token,
            'file' => $file,
            'for_date' => '2022'//date('Y-m')
        ])->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);

    }

}

