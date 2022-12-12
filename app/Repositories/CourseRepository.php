<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\Course;
use App\Models\CoursePattern;
use App\Models\CourseSchedule;
use App\Models\DriverCourse;
use App\Models\Point;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);

    }

    /**
     * Instantiate model
     *
     * @param Course $model
     */

    public function model()
    {
        return Course::class;
    }

    /** get list Course
     * @param $request
     * @return array|mixed|null
     */
    public function listCourse($request)
    {
        $sortby = $request['sortby'] ?? null;
        $field = $request['field'] ?? null;
        $listCourse = $this->getListCourses($field, $sortby);

        foreach ($listCourse as &$course) {
            if ($course->status == 'on' && strtotime($course->end_date) && strtotime($course->end_date) < strtotime(date('Y-m-d'))) {
                $course->status = 'off';
                Course::where('id', $course->id)->update(['status' => $course->status]);
            }
        }

        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $listCourse);
    }

    private function getListCourses($field = '', $sortby = '')
    {
        $date = date('Y-m-d');
        $query = Course::select('*')
            ->SelectRaw('IF( status = "off" OR end_date < ?, "yes", "no") AS highlight', [
                $date
            ])
            ->orderBy('highlight');
        if ($field && $sortby) {
            $query->orderBy($field, $sortby);
        }
        return $query->orderBy('id')->get();
    }

    public function editOrCreate(array $attributes)
    {

        $flag = $attributes['flag'] ?? Course::COURSE_FLAG_NO;
        $pot = $attributes['pot'] ?? Course::COURSE_FLAG_NO;
        $courseCode = $attributes['course_code'];
        $courseName = $attributes['course_name'] ?? null;
        $startTime = $attributes['start_time'] ?? null;
        $endTime = $attributes['end_time'] ?? null;
        $breakTime = $attributes['break_time'] ?? null;
        $startDate = $attributes['start_date'] ?? null;
        $endDate = $attributes['end_date'] ?? null;
        $note = $attributes['note'] ?? null;
        $group = $attributes['group'] ?? null;
        $point = $attributes['point'] ?? null;
        $status = strtotime($endDate) && strtotime($endDate) < strtotime(date('Y-m-d')) ? Course::COURSE_STATUS_OFF: Course::COURSE_STATUS_WORK;
        $model = Course::updateOrCreate([
            'course_code' => $courseCode
        ],[
            'flag' => $flag,
            'pot' => $pot,
            'course_code' => $courseCode,
            'course_name' => $courseName,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'break_time' => $breakTime,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'note' => $note,
            'group' => $group,
            'point' => $point
        ]);
        if (!$model) {
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
        }

        Point::updateOrCreate([
            'date' => date('Y-m-d'),
            'course_code' => $model->course_code,
        ],[
            'date' => date('Y-m-d'),
            'course_code' => $model->course_code,
            'point' => $model->point
        ]);

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $model);
    }


    /**
     * @param array $attributes
     * $attributes = ['flag' => "",course_code => "",course_name=> "" ,start_time =>"",end_time=> "",break_time=> "", start_date =>"",end_date=> "",
     * note=>"note"];
     * @return mixed|void
     */
    public function create(array $attributes)
    {
        $theCourse = $this->editOrCreate($attributes);

        CoursePatternRepository::makeData();

        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $theCourse['data']);
    }

    /**
     * @param array $attributes
     * $attributes = ['flag' => "",course_code => "",course_name=> "" ,start_time =>"",end_time=> "",break_time=> "", start_date =>"",end_date=> "",
     * note=>"note"];
     * @return mixed|void
     */
    public function update(array $attributes, $id)
    {
        $theCourse = Course::find($id);
        if (!$theCourse) {
            return ResponseService::responseData(CODE_NOT_FOUND, 'error', trans('errors.data_not_found'));
        }

        $theCourse = $this->editOrCreate($attributes);

        CoursePatternRepository::makeData($theCourse['data']);

        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $theCourse['data']);
    }

    /** get detail course
     * @param $id
     * @return array|mixed|null
     */
    public function getOne($id)
    {
        $course = $this->model->with([
            'owner'  => function ($query) {
                $query->select('driver_code', 'course_code');
            },
            'owner.driver'  => function ($query) {
                $query->select('driver_code', 'driver_name');
            }
        ])->find($id);
        if (!$course) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $course);
    }

    /**
     * @param $id
     * @return array|int|mixed|null
     */
    public function delete($id)
    {
        try {
            $dayNow = Carbon::parse(now())->toDateString();
            $course = $this->model->find($id);
            if (!$course) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
            // delete driver_course
            DriverCourse::where('course_code',$course->course_code)->delete();
            // delete course parten
            CoursePattern::where('course_parent_code',$course->course_code)->orWhere('course_child_code',$course->course_code)->delete();
            // course sche
            CourseSchedule::where('course_code',$course->course_code)->delete();

            Point::where('course_code', $course->course_code)->delete();

            $course->delete();
            return ResponseService::responseData(Response::HTTP_OK, 'success', trans('messages.mes.delete_success'));
        } catch (\Exception $exception) {
            return ResponseService::responseData(Response::HTTP_OK, 'success', trans('messages.mes.delete_success'));
        }

    }
}
