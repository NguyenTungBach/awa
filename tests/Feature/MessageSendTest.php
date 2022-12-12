<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\GroupChat;
use Illuminate\Http\UploadedFile;
class MessageSendTest extends TestCase
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

        $response = $this->post('/api/auth/login', ['user_code' => "111111", 'password' => '123456789']);
        $response->assertJson(["code" => 200,], $strict = false);
        $data = json_decode($response->getContent());
        $this->token = $data->data->access_token;
        $this->createGroupChat();
    }
    public function testSendAmessageToInvalidDepartment()
    {
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 6969, // invalid group(department group chat)
            'message' => 'ơ kìa, trông kia đàn gà con lông vàng'
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "status" => 500,
                "message" => "Group not found"
            ]
        ], $strict = false);
    }

    public function testSendAMessageWithBlankGroupId() {
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => null, // invalid group(department group chat)
            'message' => 'hay la doi ta hen uoc tu hu vo'
        ]);
        $response->assertJson([
            "code" => 422,
            "message" => "group id not null",
            "message_content" => null,
            "message_internal" => [
                "group_id" => [
                    "group id not null"
                ]
            ],
            "data_error" => null
        ], $strict = false);
    }

    public function testSendAMessageWithBlankMessage() {
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 1, // invalid group(department group chat)
            'message' => null
        ]);

        $response->assertJson([
            "code" => 422,
            "message" => "message not null",
            "message_content" => null,
            "message_internal" => [
                "message" => [
                    "message not null"
                ]
            ],
            "data_error" => null
        ], $strict = false);
    }

    public function testSendAMessageSuccess() {
        $message = 'hay la doi ta hen uoc tu hu vo';
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 1,
            'message' => 'hay la doi ta hen uoc tu hu vo'
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "sender_id" => 1,
                "user_code" => 111111,
                "content" => [
                    "message" => $message,
                    "file_id" => null
                ]
            ]
        ], $strict = false);
    }

    public function testSendAMessageWithHeavyFileAttatch() {
        $message = 'hay la doi ta hen uoc tu hu vo';
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 1,
            'message' => $message,
            'file' => UploadedFile::fake()->create('avatar.jpg', UPLOAD_MAX_FILE_SIZE + 1)
        ]);

        $response->assertJson([
            "code" => 422,
            "message" => "fileには、"  .    UPLOAD_MAX_FILE_SIZE ." KB以下のファイルを指定してください。",
            "message_content" => null,
            "message_internal" => [
                "file" => [
                    "fileには、"  .    UPLOAD_MAX_FILE_SIZE ." KB以下のファイルを指定してください。",
                ]
            ],
            "data_error" => null
        ], $strict = false);
    }

    public function testSendAMessageWithAFileIsEmpty() {
        $message = 'hay la doi ta hen uoc tu hu vo';
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 1,
            'message' => $message,
            'file' => null
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "sender_id" => 1,
                "user_code" => 111111,
                "content" => [
                    "message" => $message,
                    "file_id" => null //ok
                ]
            ]
        ], $strict = false);
    }

    public function testSendAMessageWithAFileSuccess() {
        $message = 'hay la doi ta hen uoc tu hu vo';
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/message', [
            'group_id' => 1,
            'message' => $message,
            'file' => UploadedFile::fake()->create('avatar.jpg', UPLOAD_MAX_FILE_SIZE)
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "sender_id" => 1,
                "user_code" => 111111,
                "content" => [
                    "message" => $message,
                    "file_id" => 1
                ]
            ]
        ], $strict = false);
    }

    private function createGroupChat() {
        $group = GroupChat::create([
            'name' => 'Group 1'
        ]);

        $group->group_chat_user()->create([
            'user_id' => 1
        ]);
    }
}
