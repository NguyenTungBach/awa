<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth;
use Tests\TestCase;
use Faker\Generator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
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
        $this->artisan('db:seed --class=UserSeeder');
        $user = User::first();
        if (!$user) {
            $user = User::factory()->count(5)->create();
        }
    }

    public function testUserListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/user?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testUserListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get("api/user?field='nis'" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByUserNameSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_name&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByUserNameFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_na&sortby=jkl' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByUserCode()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByUserCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=mmm' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByUserRole()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=role&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByUserRoleFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=role&sortby=abc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUserHasPermission()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testUserNoPermission()
    {
        $user = User::where('user_code', '=', '2233')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_FORBIDDEN);

    }

    public function testCanCreateUserSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "111111",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateUserCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateUserCodeDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "1122",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateUserCodeNotNumeric()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "abc99",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateUserCodeMoreThan4Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_code' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserCodeGreaterThan6Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22777777",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_code' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "",
            "user_code" => "22773",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserNameGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen Thi Huyen Trang Trang Trang Trang Trang",
            "user_code" => "22773",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserPasswordEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserPasswordMoreThan8Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "111",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserPasswordGreaterThan16Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "abc122333333998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserPasswordSpecialCharacters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "a!#*33338833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserRoleEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "a!#*33338833",
            "role" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'role' => [
                    ],
                ],
            ]);
    }

    public function testCreateUserRoleWrongType()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "22773",
            "password" => "a!#*33338833",
            "role" => "bigticy"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'role' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "33338833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_OK);
    }

    public function testUpdateUserFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('put', 'api/user/', [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "33338833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testUpdateUserNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "",
            "password" => "abc122998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserNameGreaterThan10Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen Thi Huyen Trang",
            "password" => "",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'user_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserPasswordMoreThan8Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "111",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserPasswordGreaterThan16Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "abc122333333998833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserPasswordSpecialCharacters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "a!#*33338833",
            "role" => "admin"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'password' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserRoleEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "a!#*33338833",
            "role" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'role' => [
                    ],
                ],
            ]);
    }

    public function testUpdateUserRoleWrongType()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen",
            "password" => "a!#*33338833",
            "role" => "bigticy"
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'role' => [
                    ],
                ],
            ]);
    }

    public function testUserDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->get('api/user/' . $id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "code",
                "data" => [
                    "id",
                    "user_code",
                    "user_name",
                    "role",
                    "jwt_active",
                    "remember_token",
                    "created_at",
                    "updated_at",
                ],
            ]);
    }

    public function testUserDetailFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->get('api/user/' . $id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }
    public function testUserDeleteSucess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $newUser = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Bimbim",
            "user_code" => "999011",
            "password" => "abc122998833",
            "role" => "admin"
        ]);
        $response = $this->actingAs($user)->json('delete', 'api/user/' . $newUser['data']['id'], ['token' => $token])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testUserDeleteThemselves()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $newUser = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen",
            "user_code" => "111111",
            "password" => "abc122998833",
            "role" => "admin"
        ]);
        $response = $this->actingAs($user)->json('delete', 'api/user/' . $user->id, ['token' => $token])
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }

    public function testUserDeleteFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = strtotime(now());
        $response = $this->actingAs($user)->json('delete', 'api/user/' . $id, ['token' => $token])
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }
}
