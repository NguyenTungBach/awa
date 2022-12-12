<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Http\Requests\DriverCourseRequest;
use App\Http\Requests\UserRequest;
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
