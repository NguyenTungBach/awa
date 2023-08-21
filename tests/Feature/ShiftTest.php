<?php


use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShiftTest extends TestCase
{

//    use RefreshDatabase;

    protected $user;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
//        $this->artisan('db:seed --class=UserSeeder');
        $user = User::first();
        if (!$user) {
            $user = User::factory()->count(5)->create();
        }
    }

//    public function testShiftListSuccess()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $this->actingAs($user);
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . '&start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04')
//            ->assertStatus(CODE_SUCCESS);
//        if (count($response->decodeResponseJson()['data'])) {
//            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testShiftListFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . 'start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04')
//            ->assertStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
//    }

    public function testShiftListEditSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => "2022-12",
            "items" => [
                [
                    "course_code" => "00001",
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => "00001",
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_OK);
    }
    public function testShiftListEditNoData()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => "2022-12",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testShiftListEditDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => null,
            "items" => [
                [
                    "course_code" => "00001",
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => "00001",
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'date' => [
                    ],
                ],
            ]);
    }

    public function testShiftListEditDateFomartFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '345345345',
            "items" => [
                [
                    "course_code" => "00001",
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => "00001",
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'date' => [
                    ],
                ],
            ]);
    }

    public function testShiftListEditDataUpdateCourseFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-12',
            "items" => [
                [
                    "course_code" => "65656565656",
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => "65656565656",
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateCourseEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-12',
            "items" => [
                [
                    "course_code" => null,
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => null,
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateDayOffEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-12',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "",
                    "driver" => "0001"
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateDayOffNotFomart()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-12',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "",
                    "driver" => "0001"
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }

    public function testShiftListEditDataUpdateDayOffOtherDateMonth()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-11',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "2022-12-01",
                    "driver" => "0001"
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => "0001"
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateDriverEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-11',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "2022-12-01",
                    "driver" => null
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => null
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateDriverMax15Char()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-11',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "2022-12-01",
                    "driver" => '656666666666666666666666666666666666'
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => '656666666666666666666666666666666666'
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }
    public function testShiftListEditDataUpdateDriverFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->post('api/shift/edits', [
            'token' => $token,
            "date" => '2022-11',
            "items" => [
                [
                    "course_code" => '00001',
                    "day" => "2022-12-01",
                    "driver" => '35636456456'
                ],
                [
                    "course_code" => '00001',
                    "day" => "2022-12-02",
                    "driver" => 'sdfsdfsdfsdf'
                ]
            ]
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                ],
            ]);
    }


//    public function testSortByShiftListDriverCodeSuccess()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . '&start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04&field=driver_code&sortby=desc')
//            ->assertStatus(CODE_SUCCESS);
//        if (count($response->decodeResponseJson()['data'])) {
//            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testSortByShiftListDriverCodeFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . '&start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04&field=drive_code&sortby=desc')
//            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
//    }
//
//    public function testSortByShiftListFlagSuccess()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . '&start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04&field=flag&sortby=desc')
//            ->assertStatus(CODE_SUCCESS);
//        if (count($response->decodeResponseJson()['data'])) {
//            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testSortByShiftListFlagFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->get('api/shift?token=' . $token . '&start_date=2022-04-30&end_date=2022-05-06&type=week&date=2022-04&field=flg&sortby=desc')
//            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
//    }
//
//    public function testCreateShiftListSuccess()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->json('get', 'api/user?field=role&sortby=desc' . '&token=' . $token)
//            ->assertStatus(CODE_SUCCESS);
//        if (count($response->decodeResponseJson()['data'])) {
//            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testCreateShiftListDateFormatFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09-666",
//            "start_date" => "",
//            "end_date" => "2022-09-30",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListEmptyStartDate()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "",
//            "end_date" => "2022-09-30",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'start_date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListStartDateAfterOrEqualDateNow()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "2022-09-01",
//            "end_date" => "2022-09-30",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'start_date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListFormatStartDateFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "345345345",
//            "end_date" => "2022-09-30",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'start_date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListEmptyEndDate()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "2022-09-24",
//            "end_date" => "",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'end_date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListFormatEndDateFalse()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "2022-09-24",
//            "end_date" => "5dfgdfgd",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'end_date' => [
//                    ],
//                ],
//            ]);
//    }
//
//    public function testCreateShiftListEndDateAfterOrEqualStartDate()
//    {
//        $user = User::where('user_code', '=', '1122')->first();
//        $token = \JWTAuth::fromUser($user);
//        $response = $this->actingAs($user)->post('api/shift', [
//            'token' => $token,
//            "date" => "2022-09",
//            "start_date" => "2022-09-24",
//            "end_date" => "2022-09-21",
//        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure([
//                "code",
//                "message",
//                "message_content",
//                "message_internal" => [
//                    'end_date' => [
//                    ],
//                ],
//            ]);
//    }

}
