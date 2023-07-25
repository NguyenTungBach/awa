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
use App\Models\User;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Response;
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

//        // Kiểm tra lần nữa driver theo driver_id có tồn tại không start
//        $driver = Driver::find($attributes['driver_id']);
//        if (!$driver){
//            return ResponseService::responseJsonError(Response::HTTP_NOT_FOUND, trans('errors.not_found',$attributes['driver_id']), trans('errors.not_found',$attributes['driver_id']));
//        }
//        // Kiểm tra lần nữa driver theo driver_id có tồn tại không end
//        // Kiểm tra lần nữa driver theo course_id có tồn tại không start
//        $course = Course::find($attributes['course_id']);
//        if (!$course){
//            return ResponseService::responseJsonError(Response::HTTP_NOT_FOUND, trans('errors.not_found',$attributes['course_id']), trans('errors.not_found',$attributes['course_id']));
//        }
//        // Kiểm tra lần nữa driver theo course_id có tồn tại không end

        // Kiểm tra duy nhất DriverCourse theo driver_id,course_id và date
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

        //Kiểm tra có nằm trong phạm vi tháng này năm nay không


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
