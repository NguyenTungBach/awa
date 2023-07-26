<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Http\Requests\DriverCourseRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Models\Course;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class DriverCourseRepository extends BaseRepository implements DriverCourseRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);

    }

    /**
     * Instantiate model
     *
     * @param DriverCourse $model
     */

    public function model()
    {
        return DriverCourse::class;
    }

    /**
     * @param DriverCourseRequest $request
     * @param $id
     * @return array|mixed|null
     */
    public function getPagination($id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
        $driverCourse = $driver->driverCourse()->with('course')->get();
        $arrayDataDriverCourse = [];
        foreach ($driverCourse as $keyDriverCourse => $valueDriverCourse) {
            $course = $valueDriverCourse->course ?? '';
            if (!$course) {
                continue;
            }
            $arrayDataDriverCourse[] = [
                "id" => $valueDriverCourse->id,
                "driver_id" => $driver->id,
                "driver_code" => $driver->driver_code,
                "course_code" => $course ? $course->course_code: '',
                "course_name" => $course ? $course->course_name: '',
                'course_status' => $course ? $course->status : '',
                'is_checked' => $valueDriverCourse->is_checked
            ];
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $arrayDataDriverCourse);
    }

    public function getAll($request)
    {
        $field = isset($request['field']) ? $request['field'] : null;
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;
        $getMonth_year = explode("-",$request->month_year);

        $arraySortby = ['asc', 'desc'];

        if (!$field && $sortby){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        }

        // Nhóm tất cả những course nằm trong driver
        $datas = $this->model->query()
            ->select(
                "driver_courses.id as driver_courses_id",
                "driver_courses.driver_id",
                "driver_courses.date",
                "drivers.driver_name",
                "drivers.type",
            )
            ->addSelect(\DB::raw('GROUP_CONCAT(driver_courses.course_id) as course_ids'))
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
            ->SortByForDriverCourse($request)
            ->groupBy("driver_courses.driver_id","driver_courses.date")
            ->whereYear("driver_courses.date",$getMonth_year[0])
            ->whereMonth("driver_courses.date",$getMonth_year[1])
            ->whereNull('driver_courses.deleted_at')->get()
            ->filter(function ($data) {
                switch ($data['driver']['type']){
                    case 1:
                        $data['driver']['typeName'] = trans('drivers.type.1');
                        break;
                    case 2:
                        $data['driver']['typeName'] = trans('drivers.type.2');
                        break;
                    case 3:
                        $data['driver']['typeName'] = trans('drivers.type.3');
                        break;
                    case 4:
                        $data['driver']['typeName'] = trans('drivers.type.4');
                        break;
                };
                return $data;
            });

        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);
    }

    public function totalOfExtraCost($request)
    {
        $month_year = $request->month_year;
        $startDate = Carbon::parse($month_year."-".($request->closing_date+1))->subMonth()->format('Y-m-d');
        $endDate = Carbon::parse($month_year."-".$request->closing_date)->format('Y-m-d');

        // Nhóm tất cả những course nằm trong driver
        $datas = $this->model->query()
            ->select(
                "driver_courses.driver_id",
                "drivers.driver_name",
                "drivers.type",
            )
            ->addSelect(\DB::raw("GROUP_CONCAT(driver_courses.course_id) as course_ids,SUM(CASE WHEN
            `driver_courses`.`date` BETWEEN '$startDate' AND '$endDate'
            THEN (`courses`.`meal_fee` + `courses`.`commission`) ELSE 0 END)
            as `total_money`"))
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->groupBy("driver_courses.driver_id")
            ->whereNull('driver_courses.deleted_at')->get();

        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);
    }

    public function create(array $attributes)
    {
        $items = $attributes["items"];

        // Kiểm tra trong mảng có đang duplicate không start
        $uniqueItems = array_map(function ($item) {
            return $item['course_id'] . '|' . $item['date'];
        }, $items);
        $countedItems = array_count_values($uniqueItems);

        // Lấy ra
        $duplicates = array_filter($countedItems, function ($count) {
            return $count > 1;
        });
        if (!empty($duplicates)) {
            $duplicates_key_first = explode('|',array_key_first($duplicates));
//            $duplicates_value_first = $duplicates[$duplicates_key_first];
            return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                trans('errors.duplicate_course_id_and_date',[
                    "course_id"=> $duplicates_key_first[0],
                    "date"=> $duplicates_key_first[1]
                ]));
        }
        // Kiểm tra trong mảng có đang duplicate không end


        // Kiểm tra có được phép tạo không, xem trong bảng start
        foreach ($items as $item){
            $checkDriver_id = $attributes['driver_id'];
            $checkCourse_id = $item['course_id'];
            $checkDate = $item['date'];
            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');

            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
                ->exists();
            // Nếu có tồn tại (không là duy nhất)
            if ($checkFinalClosingHistories){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.final_closing_histories" ,[
                        "attribute"=> "driver_id: $checkDriver_id, course_id: $checkCourse_id, and date: $checkDate"
                    ]));
            }
        }
        // Kiểm tra có được phép tạo không, xem trong bảng end

        // Kiểm tra duy nhất DriverCourse theo driver_id,course_id và date start
        foreach ($items as $item){
            $checkDriver_id = $attributes['driver_id'];
            $checkCourse_id = $item['course_id'];
            $checkDate = $item['date'];

            $checkUnique = DriverCourse::where('driver_id', $checkDriver_id)
                ->where('course_id', $checkCourse_id)
                ->where('date', $checkDate)
                ->exists();
            // Nếu có tồn tại (không là duy nhất)
            if ($checkUnique){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.unique" ,[
                        "attribute"=> "driver_id: $checkDriver_id, course_id: $checkCourse_id, and date: $checkDate"
                    ]));
            }
        }
        // Kiểm tra duy nhất DriverCourse theo driver_id,course_id và date end


        // Lưu lại nếu thỏa mãn tất cả điều kiện
        foreach ($items as $item){
            $driver_course = new DriverCourse();
            $driver_course->driver_id = $attributes['driver_id'];
            $driver_course->course_id = $item['course_id'];
            $driver_course->date = $item['date'];
            $driver_course->start_time = $item['start_time'];
            $driver_course->end_time = $item['end_time'];
            $driver_course->break_time = $item['break_time'];
            $driver_course->status = 1;
            $driver_course->save();
        }

        return ResponseService::responseJson(200, new BaseResource($attributes));
    }

    public function updateData($data, $theDriver)
    {
        DriverCourse::where('driver_code', $theDriver->driver_code)->delete();

        if ($data['items']) {
            foreach ($data['items'] as $item) {
                $model = new DriverCourse();
                $model->driver_code = $theDriver->driver_code;
                $model->course_code = $item['course_code'];
                $model->is_checked = $item['is_checked'] ?? 'no';
                $model->save();
            }
        }

        return $this->getPagination($theDriver->id);

        // return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $arrayDataDriverCourse);
    }

}
