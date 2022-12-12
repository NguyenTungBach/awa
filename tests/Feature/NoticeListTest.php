<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Notices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
class NoticeListTest extends TestCase
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

        $this->toke = $data->data->access_token;
        $this->mockingData();
    }

    public function testGetListSuccess() {
        $response = $this->get('/api/notices', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testGetListFilterByCreatedBy() {
        $response = $this->get('/api/notices?created_by=Super', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testGetListFilterBySubject() {
        $response = $this->get('/api/notices?subject=subject of the notice 1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals(1, count($res->data));
    }

    public function testGetListFilterIsDraft() {
        $response = $this->get('/api/notices?isdraft=1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals(2, count($res->data));
    }

    public function testGetListFilterIsNotDraft() {
        $response = $this->get('/api/notices?isdraft=2', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals(1, count($res->data));
    }

    public function testGetListFilterStartDateEndDate() {
        $response = $this->get('/api/notices?start=2021-04-01&end=2023-05-01', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals(3, count($res->data));
    }

    public function testSortByCreatedDate() {
        $response = $this->get('/api/notices?per_page=20&sortby=public_date&sorttype=1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testSortByIsDraft() {
        $response = $this->get('/api/notices?per_page=20&sortby=is_draft&sorttype=1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testSortByCreatedBy() {
        $response = $this->get('/api/notices?per_page=20&sortby=created_by&sorttype=1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($res->data));
    }

    public function testDownloadResultOfSurvey() {
        $response = $this->get('/api/notice/download/survey?notice_id=1', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function checkView() {
        $response = $this->get('/api/notices', [
            'Authorization' =>  $this->token
        ]);

        $res = json_decode($response->getContent());
        $this->assertEquals(3, count($res->data));
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

        File::create([
            "file_name" => 'c',
            "file_extension" => 'csv',
            "file_path" => '/a/b/c.csv',
            "file_size" => 1024
        ]);

        File::create([
            "file_name" => 'd',
            "file_extension" => 'csv',
            "file_path" => '/a/b/d.csv',
            "file_size" => 1024
        ]);
    }
}
