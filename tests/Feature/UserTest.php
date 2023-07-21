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
        $response = $this->actingAs($user)->get("api/user?sort=desc?abc" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testSortByUserNameSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?order_by=user_name&sort=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByUserNameFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?order_by=user_na&sort=jkl' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function estSortByUserCode()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function estSortByUserCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=mmm' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function estSortByUserRole()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=role&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function estSortByUserRoleFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=role&sortby=abc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function estUserHasPermission()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function estUserNoPermission()
    {
        $user = User::where('user_code', '=', '2233')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/user?field=user_code&sortby=desc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_FORBIDDEN);

    }

    public function estCanCreateUserSuccess()
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

    public function estCreateUserCodeEmpty()
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

    public function estCreateUserCodeDuplicate()
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

    public function estCreateUserCodeNotNumeric()
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

    public function estCreateUserCodeMoreThan4Characters()
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

    public function estCreateUserCodeGreaterThan6Characters()
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

    public function estCreateUserNameEmpty()
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

    public function estCreateUserNameGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/user', [
            'token' => $token,
            "user_name" => "Nguyen hi Huyen rang rang rang rang rang",
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

    public function estCreateUserPasswordEmpty()
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

    public function estCreateUserPasswordMoreThan8Characters()
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

    public function estCreateUserPasswordGreaterThan16Characters()
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

    public function estCreateUserPasswordSpecialCharacters()
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

    public function estCreateUserRoleEmpty()
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

    public function estCreateUserRoleWrongType()
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

    public function estUpdateUserSuccess()
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

    public function estUpdateUserFalse()
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

    public function estUpdateUserNameEmpty()
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

    public function estUpdateUserNameGreaterThan10Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = $user->id;
        $response = $this->actingAs($user)->json('put', 'api/user/' . $id, [
            'token' => $token,
            "user_name" => "Nguyen hi Huyen rang",
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

    public function estUpdateUserPasswordMoreThan8Characters()
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

    public function estUpdateUserPasswordGreaterThan16Characters()
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

    public function estUpdateUserPasswordSpecialCharacters()
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

    public function estUpdateUserRoleEmpty()
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

    public function estUpdateUserRoleWrongType()
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

    public function estUserDetailSuccess()
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

    public function estUserDetailFalse()
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
    public function estUserDeleteSucess()
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

    public function estUserDeleteThemselves()
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

    public function estUserDeleteFalse()
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
