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
            'person_charge' => 'Person charge 01',
            'post_code' => '123-4567',
            'address' => 'Address 01',
            'phone' => '01234567891',
            'note' => NULL,
            'status' => NULL,
        ]);
        Customer::factory()->create([
            'customer_code' => '0002',
            'customer_name' => 'Customer 02',
            'closing_date' => '2',
            'person_charge' => 'Person charge 02',
            'post_code' => '456-7890',
            'address' => 'Address 02',
            'phone' => '01234567892',
            'note' => NULL,
            'status' => NULL,
        ]);
    }
}
