<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\Notices;
use Illuminate\Support\Carbon;

class SeenNoticeTest extends TestCase
{
    /**
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
    public function testConfirmToNotExistNotice()
    {
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/notices/seen/no-survey', [
            'notice_id' => 6969, // invalid group(department group chat)
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "message" => "Notice not found"
            ]
        ], $strict = false);
    }


    public function testConfirmNoticeWithoutAnswerSurvey()
    {
        $notice = Notices::create([
            'subject' => 'subject',
            'content' => 'content',
            'is_draft' => '1',
            'list_files' => null,
            'has_survey' => 1,
            'public_date' => Carbon::now(),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/notices/seen/no-survey', [
            'notice_id' => 1, // invalid group(department group chat)
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "message" => "You must to answer the survey to submit"
            ]
        ], $strict = false);
    }

    public function testConfirmNoticeHasSurveySuccess()
    {
        $create = $this->withHeader('Authorization', $this->token)->postJson('api/notices', [
            "subject" => "Notices",
            "content" => "trả lời đi",
            "is_draft" => 1, //public
            "surveys" => '[{"question_content":"bao giờ hết simp","type":1,"answer":[{"position":0,"answer_content":"Câu trả lời 0"},{"position":1,"answer_content":"Câu trả lời 1"},{"position":2,"answer_content":"Câu trả lời 2"}]},{"question_content":"Câu trả lời bằng comment","type":3}]'
        ]);

        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/notices/seen/survey-answer', [
            "notice_id" => 1,
            "answer" => [
                [
                    "question_id" => 1,
                    "answer_id" => [
                        6
                    ],
                    "comment" => null
                ],
                [
                    "question_id" => 2,
                    "answer_id" => null,
                    "comment" => "thuong nho mot nguoi chang biet den ta"
                ]
            ]
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "message" => "Answer successs"
            ]
        ], $strict = false);
    }

    public function testConfirmNoticeIsDraftHasSurvey()
    {
        $create = $this->withHeader('Authorization', $this->token)->postJson('api/notices', [
            "subject" => "Notices",
            "content" => "trả lời đi",
            "is_draft" => 2, //draft
            "surveys" => '[{"question_content":"bao giờ hết simp","type":1,"answer":[{"position":0,"answer_content":"Câu trả lời 0"},{"position":1,"answer_content":"Câu trả lời 1"},{"position":2,"answer_content":"Câu trả lời 2"}]},{"question_content":"Câu trả lời bằng comment","type":3}]'
        ]);

        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/notices/seen/survey-answer', [
            "notice_id" => 1,
            "answer" => [
                [
                    "question_id" => 1,
                    "answer_id" => [
                        6
                    ],
                    "comment" => null
                ],
                [
                    "question_id" => 2,
                    "answer_id" => null,
                    "comment" => "thuong nho mot nguoi chang biet den ta"
                ]
            ]
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "message" => "Notice not found"
            ]
        ], $strict = false);
    }

    public function testConfirmNoticeIsDraftWithoutSurvey()
    {
        $notice = Notices::create([
            'subject' => 'subject',
            'content' => 'content',
            'is_draft' => '2', //draf
            'list_files' => null,
            'has_survey' => 0,
            'public_date' => Carbon::now(),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $response = $this->withHeaders(['Authorization' => $this->token, 'Content-Type' => 'multipart/form-data'])->post('/api/notices/seen/no-survey', [
            'notice_id' => 1, // invalid group(department group chat)
        ]);

        $response->assertJson([
            "code" => 200,
            "data" => [
                "message" => "Notice not found"
            ]
        ], $strict = false);
    }
}
