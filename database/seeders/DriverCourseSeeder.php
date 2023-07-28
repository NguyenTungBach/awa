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
            do {
                // Lấy ngẫu nhiên lịch trình
                $courseRandom = Course::all()->random();

                // Kiểm tra duy nhất DriverCourse theo driver_id, course_id và date
                $checkDriver_id = $driverId;
                $checkCourse_id = $courseRandom->id;

//                    // Lấy ngẫu nhiên trong khoảng hôm nay đến 7 ngày trước
//                    $randomNumberOfDays = rand(0, 7);
//                    $aboutSevenDaysAgo = Carbon::now()->subDays($randomNumberOfDays);
//                    $checkDate = $aboutSevenDaysAgo;

                /*
                 * Kiểm tra driver nào chưa nghỉ hưu và đã được chỉ định course này
                 * và course được chỉ định phải trùng ngày với ship_date của courses
                 */

//                    // Kiểm tra xem course có đúng ngày ship_date không
//                    $checkCourse = true;
                $course = Course::find($checkCourse_id);
//                    if ($course->ship_date != $checkDate){
//                        $checkCourse = false;
//                    }

                // Kiểm tra course này đã tồn tại chưa và driver_courses này có drivers chưa nghỉ hưu
                $checkUnique = DriverCourse::
                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//                        ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                    ->where('driver_courses.course_id', $checkCourse_id)
//                        ->where('driver_courses.date', $checkDate)
                    ->whereNull('drivers.end_date')
                    ->exists();

                if ($checkCourse_id == 1){
                    $checkUnique = true;
                }
            } while ($checkUnique);

            DriverCourse::create([
                "driver_id" => $driverId,
                "course_id" => $checkCourse_id,
                "date" => $course->ship_date,
                "start_time" => $courseRandom->start_date,
                "break_time" => $courseRandom->break_time,
                "end_time" => $courseRandom->end_date,
                "status" => 1,
            ]);
        }
    }
}
