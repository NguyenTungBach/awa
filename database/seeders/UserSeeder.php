<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        User::factory()->create([
            'user_code' => '1122',
            'user_name' => 'Super Admin',
            'password' => Hash::make('abc12345678'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'user_code' => '2233',
            'user_name' => 'Member Drive',
            'password' => Hash::make('abc12345678'),
            'role' => 'driver',
        ]);
    }
}
