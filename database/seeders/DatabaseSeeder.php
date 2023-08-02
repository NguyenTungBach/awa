<?php

namespace Database\Seeders;

use App\Models\DriverCourse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(CourseSeeder::class);
//        $this->call(DriverCourseSeeder::class);
    }
}
