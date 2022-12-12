<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Faker\Factory as Faker;

class NoticeUpdateTest extends TestCase
{
    /**cvxbn
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $token;
    protected $faker;

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
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])
            ->post('api/notices', [
                "subject" => "Notices mơ màng ngày ta và người",
                "content" => "trả lời đi",
                "is_draft" => "0: là notice thường, 1: là notice nháp",
                "surveys" => '[{"question_content":"bao giờ hết simp","type":"1: check box, 2: radio, 3: text only","answer":[{"position":0,"answer_content":"Câu trả lời 0"},{"position":1,"answer_content":"Câu trả lời 1"},{"position":2,"answer_content":"Câu trả lời 2"}]},{"question_content":"Câu trả lời bằng comment","type":3}]',
                'attach_files' => [$file1, $file2],
            ]);
        return $response;
    }

    public function testUpdateNoticeBlank()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $response = $this->withHeader('Authorization', $this->token)->postJson('api/notices/' . $id, []);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeValidateSubject()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $response = $this->withHeader('Authorization', $this->token)->postJson('api/notices/' . $id, [
            'data_notice' => '{"content":"trả lời đi","surver":[{"question_content":"bao giờ hết simp","type":1,"answer":[{"answer_content":"người mình yêu, thì không yêu mình"},{"answer_content":"Không bao giờ bé ơi"},{"answer_content":"le minh huong"}]},{"question_content":"bao giờ hết simp","type":3}]}'
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeValidateContent()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $response = $this->withHeader('Authorization', $this->token)->postJson('api/notices/' . $id, [
            'data_notice' => '{"subject":"Notices mơ màng ngày ta và người","surver":[{"question_content":"bao giờ hết simp","type":1,"answer":[{"answer_content":"người mình yêu, thì không yêu mình"},{"answer_content":"Không bao giờ bé ơi"},{"answer_content":"le minh huong"}]},{"question_content":"bao giờ hết simp","type":3}]}'
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeManyWordContent()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $response = $this->withHeader('Authorization', $this->token)->postJson('api/notices/' . $id, [
            'data_notice' => '{"subject":"Notices mơ màng ngày ta và người","content":"' . $this->faker->text($maxNbChars = 500) . '","surver":[{"question_content":"bao giờ hết simp","type":1,"answer":[{"answer_content":"người mình yêu, thì không yêu mình"},{"answer_content":"Không bao giờ bé ơi"},{"answer_content":"le minh huong"}]},{"question_content":"bao giờ hết simp","type":3}]}'
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeWithOneFile()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices/' . $id, [
            'subject' => "Notices mơ màng ngày ta và người",
            "content" => "trả lời đi",
            'attach_files' => [$file],
        ]);
        Storage::disk()->assertExists($response->getData()->data->list_file_display[0]->file_path);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);
    }

    public function testUpdateNoticeMultipleFile()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;
        $file1 = UploadedFile::fake()->image('avatar.jpg');
        $file2 = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices/' . $id, [
            'subject' => "Notices mơ màng ngày ta và người",
            "content" => "trả lời đi",
            'attach_files' => [$file1, $file2],
        ]);
        Storage::disk()->assertExists($response->getData()->data->list_file_display[0]->file_path);
        Storage::disk()->assertExists($response->getData()->data->list_file_display[1]->file_path);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);
    }

    public function testUpdateNoticeWithValidateFile()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;

        $file1 = UploadedFile::fake()->create('avatar.php');
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices/' . $id, [
            'data_notice' => '{"subject":"Notices mơ màng ngày ta và người","content":"trả lời đi"}',
            'attach_files' => [$file1],
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeWithValidateFileSize()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;

        $file1 = UploadedFile::fake()->create('avatar.png', 70000);
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices/' . $id, [
            'data_notice' => '{"subject":"Notices mơ màng ngày ta và người","content":"trả lời đi"}',
            'attach_files' => [$file1],
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testUpdateNoticeWithDataNoticeSuccess()
    {
        $dataInit = $this->initDataTest();
        $id = $dataInit->getData()->data->id;

        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('api/notices/' . $id, [
            'subject' => "Notices mơ màng ngày ta và người",
            "content" => "trả lời đi",
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);
    }
}
