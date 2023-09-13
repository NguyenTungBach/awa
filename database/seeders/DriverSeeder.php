<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //      DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('drivers')->truncate();
        DB::table('drivers')->insert([
            [
                'driver_code' => '0001',
                'driver_name' => 'Manager',
                'type' => 1,
                'start_date' => '2022-06-16',
                'car' => '0011',
                'status' => 1,
            ],
            [
                'driver_code' => '0002',
                'driver_name' => 'Part-time',
                'type' => 2,
                'start_date' => '2022-07-17',
                'car' => '0022',
                'status' => 1,
            ],
            [
                'driver_code' => '0003',
                'driver_name' => 'Full time',
                'type' => 3,
                'start_date' => '2022-08-18',
                'car' => '0033',
                'status' => 1,
            ],
            [
                'driver_code' => '0004',
                'driver_name' => 'Associate company',
                'type' => 4,
                'start_date' => '2022-09-19',
                'car' => '0044',
                'status' => 1,
            ],
        ]);
//      DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
