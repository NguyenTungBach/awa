<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportDriverTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $user = User::where('user_code', '=', '1122')->first();
        if (!$user) {
            User::CreateDriver('1122', 'Super Admin', 'abc12345678', 'admin');
        }
    }

    public function testReportDriverListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', '/api/parctical-performance', [
            'token' => $token
        ])
        ->assertStatus(CODE_SUCCESS);
    }

    public function testReportDriverListFormatParamsFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', '/api/parctical-performance', [
            'token' => $token,
            'sortby_code' => 'ásssssssss'
        ])
        ->assertStatus(CODE_SUCCESS);
    }

    public function testReportDriverListMissToken()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', '/api/parctical-performance', [
            // 'token' => $token
        ])
        ->assertStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }


}
