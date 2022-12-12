<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Notices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class MobileNoticeListTest extends TestCase
{
    /**cvxbn
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->faker = Faker::create();

        $response = $this->post('/api/auth/login', [
            'user_code' => "111111",
            'password' => '123456789'
        ]);
        $response->assertJson([
            "code" => 200,
        ], $strict = false);

        $data = json_decode($response->getContent());

        $response = $this->get('/api/auth/user', [
            'Authorization' => $data->data->access_token
        ]);

        $this->token = $data->data->access_token;
        $this->mockingData();
    }

    public function testGetListSuccess() {
        $response = $this->withHeader('Authorization', $this->token)->get('/api/mobile/notices');
        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testGetListWithPage() {
        $response = $this->withHeader('Authorization', $this->token)->get('/api/mobile/notices?page=2');
        $res = json_decode($response->getContent());
//        $this->assertGreaterThan(0, count($res->data));
        $response->assertJson(["pagination" => ["current_page" => "2"]]);
    }

    public function testGetListWithPerPage() {
        $response = $this->withHeader('Authorization', $this->token)->get('/api/mobile/notices?page=1&per_page=50');
        $res = json_decode($response->getContent());
//        $this->assertGreaterThan(0, count($res->data));
        $response->assertJson(["pagination" => ["current_page" => "1"]]);
        $response->assertJson(["pagination" => ["per_page" => "50"]]);
    }

    private function mockingData() {
        $notice = Notices::create([
            'subject' => 'subject of the notice 1',
            'content' => 'contents of the notice 1',
            'is_draft' => 1,
            'list_files' => [1],
            'public_date' => '2022-04-30 01:01:01',
            'created_by' => 1,
            'updated_by' => 2
        ]);

        $notice = Notices::create([
            'subject' => 'subject of the notice 2 draft',
            'content' => 'contents of the notice 2 draft',
            'is_draft' => 2,
            'list_files' => [2],
            'public_date' => null,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        $notice = Notices::create([
            'subject' => 'subject of the notice 3',
            'content' => 'contents of the notice 3',
            'is_draft' => 1,
            'list_files' => [1],
            'public_date' => '2022-04-30 01:01:01',
            'created_by' => 2,
            'updated_by' => 2
        ]);
    }
}
