<?php

namespace Database\Seeders;

use App\Models\DriverCourse;
use App\Models\ResultAI;
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
        $this->call(DepartmentSeeder::class);
        $this->call(RoleSeeder::class);
        //$this->call(PermissionTableSeeder::class);
        //$this->call(AssignPermissionsToRolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DriverTableSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(DriverCourseSeeder::class);
        $this->call(CourseScheduleSeeder::class);
        $this->call(ResultAISeeder::class);
        $this->call(ReportDriverSeeder::class);
    }
}
