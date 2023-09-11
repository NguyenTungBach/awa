<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth;
use Tests\TestCase;
use Faker\Generator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerTest extends TestCase
{
    // use RefreshDatabase;

    protected $customer;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        if (!$user) {
            $user = User::factory()->count(5)->create();
        }
        $customer = Customer::first();
        if (!$customer) {
            $customer = Customer::factory()->count(5)->create();
        }
    }

    public function testCustomerListSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $this->actingAs($user);
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get('api/customer?token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testCustomerListFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->get("api/customer?sort_by=abc" . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByCustomerNameSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=customer_name&sort_by=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByCustomerNameFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=customer_nameaaa&sort_by=desc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByCustomerCode()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=customer_code&sort_by=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByCustomerCodeFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=customer_codeabc&sort_by=desc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSortByCustomerClosingDate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=closing_date&sort_by=desc' . '&token=' . $token)
            ->assertStatus(CODE_SUCCESS);
        if (count($response->decodeResponseJson()['data'])) {
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testSortByCustomerClosingDateFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('get', 'api/customer?order_by=closing_dateabc&sort_by=desc' . '&token=' . $token)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanCreateCustomerSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '123456',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(CODE_SUCCESS);
    }

    public function testCreateCustomerCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateCustomerCodeDuplicate()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '0001',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateCustomerCodeNotNumeric()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => 'abc',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_code' => [

                    ],
                ],
            ]);
    }

    public function testCreateCustomerCodeGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678954236178542962',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_code' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => '',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerNameGreaterThan20Characters()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Admin Admin Admin Admin Admin Admin Admin',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_name' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerClosingDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'closing_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerClosingDateNotExist()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'closing_date' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerPersonChargeNull()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => '',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'person_charge' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerPersonChargeNullMoreThan20Character()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => 'person charge person charge person charge person charge',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'person_charge' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerPostCodeNull()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => 'person charge',
            'post_code' => '',
            'address' => '',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'post_code' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerAdressNull()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => 'person charge',
            'post_code' => '123-4567',
            'address' => '',
            'phone' => '01212341234',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'address' => [
                    ],
                ],
            ]);
    }

    public function testCreateCustomerPhoneNull()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $response = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '12345678',
            'customer_name' => 'Customer',
            'closing_date' => '5',
            'tax' => '1',
            'person_charge' => 'person charge',
            'post_code' => '123-4567',
            'address' => 'address',
            'phone' => '',
            'note' => NULL,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'phone' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            'customer_name' => 'Customer update',
        ])->assertStatus(Response::HTTP_OK);
    }

    public function testUpdateCustomerFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "customer_code" => "0002",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateCustomerNameEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "customer_name" => "",
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'customer_name' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerClosingDateEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "closing_date" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'closing_date' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerPersonChargeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "person_charge" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'person_charge' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerPostCodeEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "post_code" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'post_code' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerAddressEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "address" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'address' => [
                    ],
                ],
            ]);
    }

    public function testUpdateCustomerPhoneEmpty()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->json('put', 'api/customer/' . $customer->id, [
            'token' => $token,
            "phone" => ""
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal" => [
                    'phone' => [
                    ],
                ],
            ]);
    }

    public function testCustomerDetailSuccess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $customer = Customer::first();
        $response = $this->actingAs($user)->get('api/customer/' . $customer->id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "id",
                    "customer_code",
                    "customer_name",
                    "closing_date",
                    "person_charge",
                    "post_code",
                    "address",
                    "phone",
                    "note",
                    "status",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                ],
            ]);
    }

    public function testCustomerDetailFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = 10000;
        $response = $this->actingAs($user)->get('api/customer/' . $id, ['HTTP_Authorization' => 'Bearer' . $token])
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }

    public function testCustomerDeleteSucess()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $newCustomer = $this->actingAs($user)->json('post', 'api/customer', [
            'token' => $token,
            'customer_code' => '1234567890',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
        $response = $this->actingAs($user)->json('delete', 'api/customer/' . $newCustomer['data']['id'], ['token' => $token])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCustomerDeleteFalse()
    {
        $user = User::where('user_code', '=', '1122')->first();
        $token = \JWTAuth::fromUser($user);
        $id = 10000;
        $response = $this->actingAs($user)->json('delete', 'api/customer/' . $id, ['token' => $token])
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                "code",
                "message",
                "message_content",
                "message_internal",
                "data_error",
            ]);
    }
}
