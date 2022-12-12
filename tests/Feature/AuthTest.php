<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

class AuthTest extends TestCase
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
        $this->faker = Faker::create();
    }

    public function testLoginSuccess()
    {
        $response = $this->post('/api/auth/login', [
            'user_code' => "1122",
            'password' => 'abc12345678'
        ]);
        $response->assertJson(["code" => 200], $strict = false);
    }

    public function testLoginWithoutPassword()
    {
        $response = $this->post('/api/auth/login', [
            'user_code' => "1122",
            'password' => ''
        ]);
        $response->assertJson([
            "code" => 422,
            "message" => "ユーザーIDとパスワードを入力してください。",
            "message_content" => null,
            "message_internal" => [
                "password" => [
                    "ユーザーIDとパスワードを入力してください。"
                ]
            ],
            "data_error" => null
        ], $strict = false);
    }

    public function testLoginWithoutAccount()
    {
        $response = $this->post('/api/auth/login', [
            'user_code' => '',
            'password' => '123456789'
        ]);
        $response->assertJson([
            "code" => 422,
            "message" => "ユーザーIDとパスワードを入力してください。",
            "message_content" => null,
            "message_internal" => [
                "user_code" => [
                    "ユーザーIDとパスワードを入力してください。"
                ]
            ],
            "data_error" => null
        ], $strict = false);
    }

    public function testUserInformation(){
        $response = $this->post('/api/auth/login', [
            'user_code' => "1122",
            'password' => 'abc12345678'
        ]);
        $response->assertJsonStructure([
            "code",
            "data" => [
                "access_token",
                "profile" => [
                    "id",
                    "user_code",
                    "user_name",
                    "role",
                ]
            ]
        ]);
    }

    public function testValidationId(){
        $response = $this->post('/api/auth/login', [
            'user_code' => "11**44",
            'password' => 'abc123456789'
        ]);
        $response->assertJsonStructure([
            "code" ,
            "message",
            "message_content",
            "message_internal" => [
                "user_code" =>[

                ]
            ],
            "data_error"
        ]);
    }
    public function testValidationPassword()
    {
        $response = $this->post('/api/auth/login', [
            'user_code' => "1122",
            'password' => 'abc123&#89000001111'
        ]);
        $response->assertJsonStructure([
            "code" ,
            "message",
            "message_content",
            "message_internal" => [
                "password" =>[

                ]
            ],
            "data_error"
        ]);
    }
    public function testLogoutSuccess()
    {
        $user = User::where('user_code','=','1122')->first();
        $token = \JWTAuth::fromUser($user);
        $this->post('api/auth/logout?token=' . $token)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertGuest('api');
    }
    public function testLogoutFalse()
    {
        $response = $this->post('/api/auth/logout');
        $response->assertJsonStructure([
            "code" ,
            "message",
            "message_content",
            "message_internal",
            "data_error"
        ]);
    }
}
