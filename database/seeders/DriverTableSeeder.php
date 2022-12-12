<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Driver::first()){
            Driver::create([
                    'flag' =>  array_rand(['lead', 'full']) ,
                    'driver_code' =>  "0001",
                    'driver_name' => "Test 1" ,
                    'start_date' => "2022-08-15",
                    'birth_day' => "2020-12-03",
                    'status' => "on" ,
                ]
            );
        }

    }
}
