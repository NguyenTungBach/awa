<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::first()) {
            User::createUser('1122','Super Admin','abc12345678','admin');
            User::createUser('2233','Member Drive','abc12345678','driver');
        }
    }
}
