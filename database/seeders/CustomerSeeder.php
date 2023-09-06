<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->truncate();
        Customer::factory()->create([
            'customer_code' => '0001',
            'customer_name' => 'Customer 01',
            'closing_date' => '1',
            'tax' => '1',
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
        Customer::factory()->create([
            'customer_code' => '0002',
            'customer_name' => 'Customer 02',
            'closing_date' => '2',
            'tax' => '2',
            'person_charge' => 'Person charge 02',
            'post_code' => '456-7890',
            'address' => 'Address 02',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
        Customer::factory()->create([
            'customer_code' => '0003',
            'customer_name' => 'Customer 03',
            'closing_date' => '3',
            'tax' => '1',
            'person_charge' => 'Person charge 03',
            'post_code' => '456-7891',
            'address' => 'Address 03',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
        Customer::factory()->create([
            'customer_code' => '0004',
            'customer_name' => 'Customer 04',
            'closing_date' => '2',
            'tax' => '2',
            'person_charge' => 'Person charge 04',
            'post_code' => '456-7890',
            'address' => 'Address 04',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
        Customer::factory()->create([
            'customer_code' => '0005',
            'customer_name' => 'Customer 05',
            'closing_date' => '4',
            'tax' => '1',
            'person_charge' => 'Person charge 05',
            'post_code' => '456-7890',
            'address' => 'Address 05',
            'phone' => '01212341234',
            'note' => NULL,
        ]);
    }
}
