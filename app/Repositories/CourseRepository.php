<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\Course;
use App\Models\FinalClosingHistories;
use App\Repositories\Contracts\CashInStaticalRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\DriverCourse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface, CashOutStatisticalRepositoryInterface
{
    public function __construct(
        Application $app,
        CashOutStatisticalRepositoryInterface $cashOutStatisticalRepository,
        CashInStaticalRepositoryInterface  $cashInStaticalRepository
    ){
        parent::__construct($app);
        $this->cashOutStatisticalRepository = $cashOutStatisticalRepository;
        $this->cashInStaticalRepository = $cashInStaticalRepository;
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

    public function createCourse($input)
    {
        $input['associate_company_fee'] = empty($input['associate_company_fee']) ? 0 : Arr::get($input, 'associate_company_fee', 0);
        $input['expressway_fee'] = empty($input['expressway_fee']) ? 0 : Arr::get($input, 'expressway_fee', 0);
        $input['commission'] = empty($input['commission']) ? 0 : Arr::get($input, 'commission', 0);
        $input['meal_fee'] = empty($input['meal_fee']) ? 0 : Arr::get($input, 'meal_fee', 0);
        $input['note'] = Arr::get($input, 'note', NULL);

        $course = Course::create([
            'customer_id' => $input['customer_id'],
            'course_name' => $input['course_name'],
            'ship_date' => $input['ship_date'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date'],
            'break_time' => $input['break_time'],
            'departure_place' => $input['departure_place'],
            'arrival_place' => $input['arrival_place'],
            'ship_fee' => $input['ship_fee'],
            'associate_company_fee' => $input['associate_company_fee'],
            'expressway_fee' => $input['expressway_fee'],
            'commission' => $input['commission'],
            'meal_fee' => $input['meal_fee'],
            'note' => $input['note'],
        ]);

        return $course;
    }

    public function getAll($input)
    {
        $input['start_date_ship'] = Arr::get($input, 'start_date_ship', NULL);
        $input['end_date_ship'] = Arr::get($input, 'end_date_ship', NULL);
        $input['customer_id'] = Arr::get($input, 'customer_id', NULL);
        $input['order_by'] = Arr::get($input, 'order_by', 'id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');

        $data = [];
        $courses = Course::with('customer');
        if (!empty($input['start_date_ship']) && !empty($input['end_date_ship'])) {
            $courses = $courses->whereBetween('ship_date', [$input['start_date_ship'], $input['end_date_ship']]);
        }
        if (empty($input['start_date_ship']) && !empty($input['end_date_ship'])) {
            $courses = $courses->where('ship_date', '<=',$input['end_date_ship']);
        }
        if (!empty($input['start_date_ship']) && empty($input['end_date_ship'])) {
            $courses = $courses->where('ship_date', '>=', $input['start_date_ship']);
        }
        if (!empty($input['customer_id'])) {
            $courses = $courses->where('customer_id', $input['customer_id']);
        }

        if ($input['order_by'] == 'customer_name') {
            $courses = $courses->join('customers', 'courses.customer_id', '=', 'customers.id');
            $courses = $courses->select('courses.*', 'customers.customer_name');
            $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        } else {
            $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        }

        $courses = $courses->whereNull('status')->get();
        if (Route::getCurrentRoute()->getActionMethod() == 'export') {
            foreach ($courses as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['ship_date'] = $value->ship_date;
                $data[$key]['course_name'] = $value->course_name;
                $data[$key]['start_date'] = date('H:i', strtotime($value->start_date));
                $data[$key]['end_date'] = date('H:i', strtotime($value->end_date));
                $data[$key]['break_time'] = date('H:i', strtotime($value->break_time));
                $data[$key]['customer_name'] = empty($value->customer) ? '' : $value->customer->customer_name;
                $data[$key]['departure_place'] = $value->departure_place;
                $data[$key]['arrival_place'] = $value->arrival_place;
                $data[$key]['ship_fee'] = $value->ship_fee;
                $data[$key]['associate_company_fee'] = $value->associate_company_fee;
                $data[$key]['expressway_fee'] = $value->expressway_fee;
                $data[$key]['commission'] = $value->commission;
                $data[$key]['meal_fee'] = $value->meal_fee;
                $data[$key]['note'] = empty($value->note) ? '' : $value->note;
            }
        } else {
            foreach ($courses as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['ship_date'] = $value->ship_date;
                $data[$key]['course_name'] = $value->course_name;
                $data[$key]['customer_name'] = empty($value->customer) ? '' : $value->customer->customer_name;
                $data[$key]['departure_place'] = $value->departure_place;
                $data[$key]['arrival_place'] = $value->arrival_place;
                $data[$key]['ship_fee'] = $value->ship_fee;
            }
        }
        $result = $data;

        return $result ?? [];
    }

    public function getDetail($id)
    {
        $result = CourseRepository::with('customer')->find($id);
        $result['customer_name'] = $result->customer->customer_name;
        $result['ship_date'] = date('Y年m月d日', strtotime($result->ship_date));
        $result['start_date'] = date('H:i', strtotime($result->start_date));
        $result['end_date'] = date('H:i', strtotime($result->end_date));
        $result['break_time'] = date('H:i', strtotime($result->break_time));
        unset($result['customer']);

        return $result;
    }

    public function updateCourse($input, $id)
    {
        try {
            DB::beginTransaction();
            $cashOut = [];
            $checkCourseId = $this->checkExistsDriverCourse($id);
            $checkFinal = $this->checkFinalClosing($id);
            // // true, false => update statistical, course
            // // true, true => not update statistical, course
            // // false => update course
            // if (!empty($input['associate_company_fee']) && $checkCourseId) {
            //     if (!$checkFinal) {
            //         $input['course_id'] = $id;
            //         unset($input['_method']);
            //         $cashOutStatistical = $this->cashOutStatisticalRepository->updateCashOutStatisticalByCourse($input);
            //         dd('cashOutStatisticalUpdate', $cashOutStatistical);

            //         dd('update course when update statis');
            //         $result = CourseRepository::update($input, $id);
            //     } else {
            //         dd(1);
            //         return 'not update course';
            //     }
            // } else {
            //     dd('update course');
            //     $result = CourseRepository::update($input, $id);
            // }
            // dd(1);

            DB::commit();

            $this->cashInStatisticalCheckUpdateIfShipFreeChange($input,$id);

//            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function cashInStatisticalCheckUpdateIfShipFreeChange($input,$id){
        // Tìm đến course kiểm tra xem có thay đổi ship_free hay không bao gồm cả trường hợp ngày đặc biệt
        $course = Course::find($id);
        // Kiểm tra Course đã được gán trong SHIFT nào chưa nếu có thì mới cần cập nhật
        $driver_courseCheck = DriverCourse::where("course_id",$id)->first();
        // Nếu thấy có thì mới update
        if ($driver_courseCheck){
            // Kiểm tra nốt trường hợp ship_fee đổi tiền
            if ($input['ship_fee'] != $course->ship_fee){
                // Cập nhật nếu thỏa mãn điều kiện trên
                // Cập nhật lại CashInStatic theo khách và ngày
                // Lưu ý đã được check trường hợp bỏ qua id đặc biệt
                $this->cashInStaticalRepository->saveCashInStatic($course->customer_id,$input['ship_date']);
            }
        }

    }

    public function deleteCourse($id)
    {
        $checkDriverCourse = $this->checkDriverCourse($id);
        if ($checkDriverCourse) {
            $courseNameError = $this->getCourseName($checkDriverCourse);

            return $this->responseJsonError(Response::HTTP_BAD_REQUEST, '削除できません: '. $courseNameError);
        }
        $result = CourseRepository::find($id)->delete();

        return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
    }

    public function destroyCourse($arrId)
    {
        $checkDriverCourse = $this->checkDriverCourse($arrId);
        if ($checkDriverCourse) {
            if (is_array($checkDriverCourse)) {
                $courseNameError = $this->getCourseName($checkDriverCourse);

                return $this->responseJsonError(Response::HTTP_BAD_REQUEST, '削除できません: '. $courseNameError);
            }

            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, '削除するコースを少なくとも 1 つ選択してください');
        }
        $result = Course::destroy($arrId);

        return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
    }

    public function checkDriverCourse($id)
    {
        if (empty($id)) {
            return $result = true;
        }
        $arrCourseId = DriverCourse::get()->pluck('course_id')->toArray();
        if (is_array($id)) {
            $result = array_intersect($arrCourseId, $id);
            $result = ($result == []) ? false : $result;
        } else {
            $result = in_array($id, $arrCourseId);
        }

        return $result;
    }

    public function getCourseName($input)
    {
        $arrCourseName = [];
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $courseName = Course::find($value);
                $arrCourseName[$key] = $courseName->course_name;
            }
            $result = implode(", ", $arrCourseName);
        } else {
            $courseName = Course::find($input);
            $result = $courseName->course_name;
        }

        return $result;
    }

    public function checkExistsDriverCourse($data)
    {
        $arrDriverCourse = DriverCourse::get()->pluck('course_id')->toArray();
        $result = in_array($data, $arrDriverCourse);

        return $result;
    }

    public function checkFinalClosing($id)
    {
        $course = Course::find($id);
        $month = date('Y-m', strtotime($course->ship_date));
        $finalClosing = FinalClosingHistories::where('type', 2)->get()->pluck('month_year')->toArray();
        $result = in_array($month, $finalClosing);

        return $result;
    }
}
