<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DriverCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Truncate xóa dữ liệu trong bảng
        DB::table('driver_courses')->truncate();
        // Lấy ra danh sách lái xe
        $listDriverIds = Driver::all()->pluck('id')->toArray();

        // Mỗi nhân viên sẽ tương ứng với Course ngẫu nhiên
        foreach($listDriverIds as $driverId){
            $randomForeach = rand(0, 7);

            for ($i = 0; $i <= $randomForeach; $i++){
                do {
                    // Lấy ngẫu nhiên lịch trình
                    $courseRandom = Course::all()->random();

                    // Kiểm tra duy nhất DriverCourse theo driver_id, course_id và date
                    $checkDriver_id = $driverId;
                    $checkCourse_id = $courseRandom->id;

                    // Lấy ngẫu nhiên trong khoảng hôm nay đến 7 ngày trước
                    $randomNumberOfDays = rand(0, 7);
                    $aboutSevenDaysAgo = Carbon::now()->subDays($randomNumberOfDays);
                    $checkDate = $aboutSevenDaysAgo;

                    $checkUnique = DriverCourse::where('driver_id', $checkDriver_id)
                        ->where('course_id', $checkCourse_id)
                        ->where('date', $checkDate)
                        ->exists();
                } while ($checkUnique);

                DriverCourse::create([
                    "driver_id" => $driverId,
                    "course_id" => $checkCourse_id,
                    "date" => $checkDate,
                    "start_time" => $courseRandom->start_date,
                    "break_time" => $courseRandom->break_time,
                    "end_time" => $courseRandom->end_date,
                    "status" => 1,
                ]);
            }
        }
    }
}
