<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\Course;
use App\Models\FinalClosingHistories;
use App\Models\Driver;
use App\Repositories\Contracts\CashInStaticalRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
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

class CourseRepository extends BaseRepository implements CourseRepositoryInterface, CashOutStatisticalRepositoryInterface, DriverCourseRepositoryInterface
{
    public function __construct(
        Application $app,
        CashOutStatisticalRepositoryInterface $cashOutStatisticalRepository,
        CashInStaticalRepositoryInterface $cashInStaticalRepository,
        DriverCourseRepositoryInterface $driverCourseRepository
    ){
        parent::__construct($app);
        $this->cashOutStatisticalRepository = $cashOutStatisticalRepository;
        $this->cashInStaticalRepository = $cashInStaticalRepository;
        $this->driverCourseRepository = $driverCourseRepository;
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
        $input['course_name'] = 'driver '.$input['driver_id'];
        $input['vehicle_number'] = empty($input['vehicle_number']) ? NULL : Arr::get($input, 'vehicle_number', NULL);
        $input['quantity'] = empty($input['quantity']) ? 0 : Arr::get($input, 'quantity', 0);
        $input['price'] = empty($input['price']) ? 0 : Arr::get($input, 'price', 0);
        $input['weight'] = empty($input['weight']) ? 0 : Arr::get($input, 'weight', 0);
        $input['ship_fee'] = empty($input['ship_fee']) ? 0 : Arr::get($input, 'ship_fee', 0);
        $input['associate_company_fee'] = empty($input['associate_company_fee']) ? 0 : Arr::get($input, 'associate_company_fee', 0);
        $input['expressway_fee'] = empty($input['expressway_fee']) ? 0 : Arr::get($input, 'expressway_fee', 0);
        $input['start_date'] = empty($input['start_date']) ? '00:00' : Arr::get($input, 'start_date', '00:00');
        $input['end_date'] = empty($input['end_date']) ? '00:00' : Arr::get($input, 'end_date', '00:00');
        $input['break_time'] = empty($input['break_time']) ? '00:00' : Arr::get($input, 'break_time', '00:00');
        $input['item_name'] = Arr::get($input, 'item_name', NULL);
        $input['note'] = Arr::get($input, 'note', NULL);

        try {
            DB::beginTransaction();
            $result = [];
            $course = [];
            $check = Common::checkValidateShift($input['driver_id'], $input['ship_date']);
            if ($check['code'] == 200) {
                $course = Course::create([
                    'customer_id' => $input['customer_id'],
                    'driver_id' => $input['driver_id'],
                    'vehicle_number' => $input['vehicle_number'],
                    'course_name' => $input['course_name'],
                    'ship_date' => $input['ship_date'],
                    'start_date' => $input['start_date'],
                    'end_date' => $input['end_date'],
                    'break_time' => $input['break_time'],
                    'departure_place' => $input['departure_place'],
                    'arrival_place' => $input['arrival_place'],
                    'item_name' => $input['item_name'],
                    'quantity' => $input['quantity'],
                    'price' => $input['price'],
                    'weight' => $input['weight'],
                    'ship_fee' => $input['ship_fee'],
                    'associate_company_fee' => $input['associate_company_fee'],
                    'expressway_fee' => $input['expressway_fee'],
                    'commission' => $input['commission'],
                    'meal_fee' => $input['meal_fee'],
                    'note' => $input['note'],
                ]);

                // create driver_course
                $driverCourse = DriverCourse::create([
                    'driver_id' => $course->driver_id,
                    'course_id' => $course->id,
                    'start_time' => $course->start_date,
                    'end_time' => $course->end_date,
                    'break_time' => $course->break_time,
                    'date' => $course->ship_date,
                    'status' => 1
                ]);

                // update cash
                $this->cashInStaticalRepository->saveCashInStatic($course->customer_id, $driverCourse->date);
                $this->driverCourseRepository->cashOutStatistical($driverCourse->driver_id, $driverCourse->date, $driverCourse->course_id);
            } else {
                $result['message'] = $check['message'];
                $result['code'] = $check['code'];
            }
            if ($course != []) {
                $result['message'] = CREATE_SUCCESS;
                $result['code'] = Response::HTTP_OK;
                $result['data'] = $course;
            }

            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
    }

    public function getAll($input)
    {
        $input['start_date_ship'] = Arr::get($input, 'start_date_ship', NULL);
        $input['end_date_ship'] = Arr::get($input, 'end_date_ship', NULL);
        $input['customer_id'] = Arr::get($input, 'customer_id', NULL);
        $input['order_by'] = Arr::get($input, 'order_by', 'id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');
        $input['month_line'] = Arr::get($input, 'month_line', Carbon::now()->format('Y-m'));

        $data = [];
        $startOfMonth = Carbon::create($input['month_line'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($input['month_line'])->endOfMonth()->format('Y-m-d');
        $courses = Course::with(['customer', 'driver'])->whereBetween('ship_date', [$startOfMonth, $endOfMonth])->whereNull('courses.status');
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
        if (!empty($input['driver_id'])) {
            $courses = $courses->where('driver_id', $input['driver_id']);
        }

        if ($input['order_by'] == 'customer_name') {
            $courses = $courses->join('customers', 'courses.customer_id', '=', 'customers.id');
            $courses = $courses->select('courses.*', 'customers.customer_name');
            $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        } elseif ($input['order_by'] == 'driver_name') {
            $courses = $courses->join('drivers', 'courses.driver_id', '=', 'drivers.id');
            $courses = $courses->select('courses.*', 'drivers.driver_name');
            $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        }else {
            $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        }

        $courses = $courses->get();
        if (Route::getCurrentRoute()->getActionMethod() == 'export') {
            foreach ($courses as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['ship_date'] = $value->ship_date;
                $data[$key]['driver_name'] = empty($value->driver) ? '' : $value->driver->driver_name;
                $data[$key]['vehicle_number'] = empty($value->vehicle_number) ? '' : $value->vehicle_number;
                $data[$key]['start_date'] = date('H:i', strtotime($value->start_date));
                $data[$key]['end_date'] = date('H:i', strtotime($value->end_date));
                $data[$key]['break_time'] = date('H:i', strtotime($value->break_time));
                $data[$key]['customer_name'] = empty($value->customer) ? '' : $value->customer->customer_name;
                $data[$key]['departure_place'] = $value->departure_place;
                $data[$key]['arrival_place'] = $value->arrival_place;
                $data[$key]['item_name'] = empty($value->item_name) ? '' : $value->item_name;
                $data[$key]['quantity'] = $value->quantity;
                $data[$key]['price'] = $value->price;
                $data[$key]['weight'] = $value->weight;
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
                $data[$key]['customer_name'] = empty($value->customer) ? '' : $value->customer->customer_name;
                $data[$key]['driver_name'] = empty($value->driver) ? '' : $value->driver->driver_name;
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
        $result = CourseRepository::with(['customer', 'driver'])->find($id);
        $result['customer_name'] = $result->customer->customer_name;
        $result['driver_name'] = $result->driver->driver_name;
        $result['vehicle_number'] = empty($result->vehicle_number) ? '' : $result->vehicle_number;
        $result['ship_date'] = date('Y年m月d日', strtotime($result->ship_date));
        $result['start_date'] = date('H:i', strtotime($result->start_date));
        $result['end_date'] = date('H:i', strtotime($result->end_date));
        $result['break_time'] = date('H:i', strtotime($result->break_time));
        unset($result['customer'], $result['driver']);

        return $result;
    }

    public function updateCourse($input, $id)
    {
        try {
            DB::beginTransaction();
            $result = [];
            $checkCourseId = $this->checkExistsDriverCourse($id);
            $checkFinal = $this->checkFinalClosing($id);
            // true, false => update statistical, course
            // true, true => not update statistical, course
            // false => update course
            if ($checkCourseId) {
                if (!$checkFinal) {
                    $checkCashInStatisticalCheckUpdateIfShipFreeChange = false;
                    // Kiểm tra nốt trường hợp ship_fee đổi tiền
                    if (!empty($input['ship_fee'])) {
                        $course = Course::find($id);
                        if ($input['ship_fee'] != $course->ship_fee){
                            $checkCashInStatisticalCheckUpdateIfShipFreeChange = true;
                        }
                    }

                    if (!empty($input['driver_id'])) {
                        $driverIdOld = Course::find($id)->driver_id;
                    }
                    // create driver_course
                    $result = CourseRepository::update($input, $id);
                    // update driver course
                    if ($result) {
                        $data = [];
                        $data['driver_id'] = $result->driver_id;
                        $data['course_id'] = $result->id;
                        $data['start_time'] = $result->start_date;
                        $data['end_time'] = $result->end_date;
                        $data['break_time'] = $result->break_time;
                        $data['date'] = $result->ship_date;
                        $data['status'] = 1;

                        $driverCourseId = DriverCourse::where('course_id', $result->id)->first()->id;
                        $driverCourse = $this->driverCourseRepository->update($data, $driverCourseId);

                        if ($driverCourse) {
                            // update cash
                            $this->cashInStaticalRepository->saveCashInStatic($result->customer_id, $driverCourse->date);
                            if (!empty($input['driver_id'])) {
                                // update cash out driver_id old
                                $this->driverCourseRepository->cashOutStatistical($driverIdOld, $driverCourse->date, $driverCourse->course_id);
                            }
                            $this->driverCourseRepository->cashOutStatistical($driverCourse->driver_id, $driverCourse->date, $driverCourse->course_id);
                        }
                        $input['course_id'] = $id;
                        unset($input['_method']);
                        
                        // if (!empty($input['associate_company_fee'])) {
                        //     $cashOutStatistical = $this->cashOutStatisticalRepository->updateCashOutStatisticalByCourse($input);
                        // }
                    }
                    if ($checkCashInStatisticalCheckUpdateIfShipFreeChange) {
                        // Cập nhật nếu thỏa mãn điều kiện trên
                        // Cập nhật lại CashInStatic theo khách và ngày
                        // Lưu ý đã được check trường hợp bỏ qua id đặc biệt
                        $this->cashInStatisticalCheckUpdateIfShipFreeChange($input,$id);
                    }
                } else {
                    return $result;
                }
            } else {
                $result = CourseRepository::update($input, $id);
            }

            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function cashInStatisticalCheckUpdateIfShipFreeChange($input,$id){
        // Trên đã check closing_history cho course
        // Tìm đến course kiểm tra xem có thay đổi ship_free hay không bao gồm cả trường hợp ngày đặc biệt
        $course = Course::find($id);
        // Kiểm tra Course đã được gán trong SHIFT nào chưa nếu có thì mới cần cập nhật
        $driver_courseCheck = DriverCourse::where("course_id",$id)->first();

        // Nếu thấy có thì mới update
        if ($driver_courseCheck){
            $this->cashInStaticalRepository->saveCashInStatic($course->customer_id,$course->ship_date);
        }

    }

    public function deleteCourse($id)
    {
        try {
            DB::beginTransaction();
            $result = [];
            $checkFinalClosing = $this->checkFinalClosing($id);
            if (!$checkFinalClosing) {
                // delete driver course
                $driverCourse = DriverCourse::where('course_id', $id)->first();
                $deleteDriverCourse = $driverCourse->delete();
                $course = CourseRepository::find($id);
                // update cash
                $this->cashInStaticalRepository->saveCashInStatic($course->customer_id, $driverCourse->date);
                $this->driverCourseRepository->cashOutStatistical($driverCourse->driver_id, $driverCourse->date, $driverCourse->course_id);
                // delete course
                $result = $course->delete();
            }
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
    }

    public function destroyCourse($arrId)
    {
        try {
            DB::beginTransaction();
            $result = [];
            $checkFinalClosing = $this->checkFinalClosing($arrId);
            if ($checkFinalClosing) {
                if (is_array($checkFinalClosing)) {
                    $result['code'] = Response::HTTP_BAD_REQUEST;
                    $result['message'] = DELETE_ERROR;
                    $result['message_content'] = '最終決算日中に存在していた';

                    return $result;
                }

                $result['code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
                $result['message'] = DELETE_ERROR;
                $result['message_content'] = '削除するコースを少なくとも 1 つ選択してください';

                return $result;
            } else {
                // delete driver course
                $driverCourses = DriverCourse::whereIn('course_id', $arrId)->get();
                $deleteDriverCourses = DriverCourse::destroy($driverCourses->pluck('id')->toArray());
                // dd($driverCourses);
                foreach ($driverCourses as $key => $driverCourse) {
                    $course = CourseRepository::find($driverCourse['course_id']);
                    // update cash
                    $this->cashInStaticalRepository->saveCashInStatic($course->customer_id, $driverCourse['date']);
                    $this->driverCourseRepository->cashOutStatistical($driverCourse['driver_id'], $driverCourse['date'], $driverCourse['course_id']);
                }
                // delete course
                $deleteCourse = Course::destroy($arrId);
                if ($deleteCourse != []) {
                    $result['code'] = Response::HTTP_OK;
                }
            }

            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
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
        if (empty($id)) {
            return $result = true;
        }
        $finalClosing = FinalClosingHistories::get()->pluck('month_year')->toArray();

        if (is_array($id)) {
            $courses = Course::findMany($id)->toArray();
            $arrMonth = [];
            foreach ($courses as $key => $value) {
                $arrMonth[$value['id']] = date('Y-m', strtotime($value['ship_date']));
            }
            $result = array_intersect($arrMonth, $finalClosing);
            $result = ($result == []) ? false : $result;
        } else {
            $course = Course::find($id);
            $month = date('Y-m', strtotime($course->ship_date));
            $result = in_array($month, $finalClosing);
        }

        return $result;
    }

    public function listCourseShift($request){
        // Lấy ra tất cả các Course đặc biệt
        // $courseSpecials = Course::
        //     select(
        //         "id",
        //         "customer_id",
        //         "course_name",
        //         "ship_date",
        //         "start_date",
        //         "end_date",
        //         "break_time",
        //     )
        //     ->where('courses.customer_id',0)
        //     ->get();

        // Lấy ra tất cả các Course trong ngày hôm đó không bao gồm Course đặc biệt
        $courseByShipDates = Course::
        select(
            "id",
            "customer_id",
            "course_name",
            "ship_date",
            "start_date",
            "end_date",
            "break_time",
        )
            ->where('courses.ship_date',$request->date)
            ->whereNotIn('courses.customer_id',[0])
            ->get()->filter(function ($data) {
                $data->start_date = Carbon::createFromFormat('H:i:s', $data->start_date)->format('H:i');
                $data->break_time = Carbon::createFromFormat('H:i:s', $data->break_time)->format('H:i');
                $data->end_date = Carbon::createFromFormat('H:i:s', $data->end_date)->format('H:i');
                return $data;
            });

        // $resultCourseShifts = $courseSpecials->concat($courseByShipDates);
        $resultCourseShifts = $courseByShipDates;

        // Lấy ra tất cả các driver_course có trong ngày hôm đó
        $driver_courses = DriverCourse::where("date",$request->date)->get();
        foreach ($driver_courses as $driver_course){
            // Nếu shift này đã được chỉ định bởi driver thì điền vào
            foreach ($resultCourseShifts as $resultCourseShift){
                $resultCourseShift['driver_id'] = null;
                if ($driver_course->course_id == $resultCourseShift['id']){
                    $resultCourseShift['driver_id'] = $driver_course->driver_id;
                }
            }
        }

        return $resultCourseShifts;
    }

    public function checkDriverAssociate($driverId)
    {
        $arrDriverAssociate = Driver::where('type', 4)->pluck('id')->toArray();
        $result = in_array($driverId, $arrDriverAssociate);

        return $result;
    }
}
