<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Faker\Factory as Faker;
use Illuminate\Http\Response;

class NoticeDeleteTest extends TestCase
{
    /**
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

        $response = $this->post('/api/auth/login', ['user_code' => "111111", 'password' => '123456789']);
        $response->assertJson(["code" => 200,], $strict = false);
        $data = json_decode($response->getContent());
        $this->token = $data->data->access_token;
    }


    public function initDataTest()
    {
        $file1 = UploadedFile::fake()->image('avatar.jpg');
        $file2 = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices', [
            'data_notice' => '{"subject":"Notices mơ màng ngày ta và người","content":"trả lời đi","is_draft":"0: là notice thường, 1: là notice nháp","surveys":[{"question_content":"bao giờ hết simp","type":"1: check box, 2: radio, 3: text only","answer":[{"position":0,"answer_content":"Câu trả lời 0"},{"position":1,"answer_content":"Câu trả lời 1"},{"position":2,"answer_content":"Câu trả lời 2"}]},{"question_content":"Câu trả lời bằng comment","type":3}]}',
            'attach_files' => [$file1, $file2],
        ]);
        return $response;
    }

    public function testDelete() {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $response = $this->delete('/api/notices/' . $id, [
            'Authorization' =>  $this->token
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);
    }
}
