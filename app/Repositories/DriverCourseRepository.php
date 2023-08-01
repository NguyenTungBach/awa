<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Http\Requests\DriverCourseRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Models\Calendar;
use App\Models\CashInStatical;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class DriverCourseRepository extends BaseRepository implements DriverCourseRepositoryInterface, CalendarRepositoryInterface
{

    public function __construct(Application $app, CalendarRepositoryInterface $calendarRepository)
    {
        parent::__construct($app);
        $this->calendarRepository = $calendarRepository;
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
        $getMonth_year = explode("-",$request->month_year);

        // Nhóm tất cả những course nằm trong driver
        $datas = $this->model->query()
            ->select(
                "driver_courses.id as driver_courses_id",
                "driver_courses.driver_id",
                "driver_courses.date",
                "drivers.driver_name",
                "drivers.driver_code",
                "drivers.type",
            )
            ->addSelect(\DB::raw('GROUP_CONCAT(driver_courses.course_id) as course_ids, GROUP_CONCAT(`courses`.`course_name`) as course_names'))
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->SortByForDriverCourse($request)
            ->groupBy("driver_courses.driver_id","driver_courses.date")
            ->whereYear("driver_courses.date",$getMonth_year[0])
            ->whereMonth("driver_courses.date",$getMonth_year[1])
            ->whereNull('driver_courses.deleted_at');

        if ($request->has('closing_date')){
            $month_year = $request->month_year;
            $startDate = Carbon::parse($month_year."-".($request->closing_date+1))->subMonth()->format('Y-m-d');
            $endDate = Carbon::parse($month_year."-".$request->closing_date)->format('Y-m-d');
            $datas->whereBetween('driver_courses.date', [$startDate, $endDate]);
        }

        $datas = $datas->get()->filter(function ($data) {
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
        return $datas;
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
                "drivers.driver_code",
                "drivers.type",
            )
            ->addSelect(\DB::raw("GROUP_CONCAT(driver_courses.course_id) as course_ids,GROUP_CONCAT(`courses`.`course_name`) as course_names
            ,SUM(CASE WHEN
            `driver_courses`.`date` BETWEEN '$startDate' AND '$endDate'
            THEN (`courses`.`meal_fee` + `courses`.`commission`) ELSE 0 END)
            as `total_money`"))
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->groupBy("driver_courses.driver_id")
            ->SortByForDriverCourse($request)
            ->whereBetween('driver_courses.date', [$startDate, $endDate])
            ->whereNull('driver_courses.deleted_at')->get()->filter(function ($data) {
                switch ($data['type']){
                    case 1:
                        $data['typeName'] = trans('drivers.type.1');
                        break;
                    case 2:
                        $data['typeName'] = trans('drivers.type.2');
                        break;
                    case 3:
                        $data['typeName'] = trans('drivers.type.3');
                        break;
                    case 4:
                        $data['typeName'] = trans('drivers.type.4');
                        break;
                };
                return $data;
            });
        return $datas;
    }


    public function getAllExpressCharge($request)
    {
        $month_year = $request->month_year;
        // Ngày đầu tháng
        $firstDayOfMonth = Carbon::createFromFormat('Y-m', $month_year)->startOfMonth();

        // Ngày cuối tháng
        $lastDayOfMonth = Carbon::createFromFormat('Y-m', $month_year)->endOfMonth();

        // Tìm tất cả các course để nhóm theo customer_id
        $datas = Course::
            select(
                "customers.id as customers_id",
                "customers.customer_code",
                "customers.closing_date",
                "customers.customer_name",
                "courses.ship_date",
            )
            ->addSelect(\DB::raw('SUM(courses.expressway_fee) as courses_expressway_fee'))
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->groupBy("customers.id","courses.ship_date")
            ->SortByForCourse($request)
            ->whereBetween('courses.ship_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotIn('courses.id', DriverCourse::ALL_ID_SPECIAL)
            ->whereNull('courses.deleted_at');

        $datas = $datas->get()->filter(function ($data) {
            switch ($data['closing_date']){
                case 1:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.1');
                    break;
                case 2:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.2');
                    break;
                case 3:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.3');
                    break;
                case 4:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.4');
                    break;
            };
            return $data;
        });
        return $datas;
    }

    public function totalOfExpressChargeCost($request)
    {
        $month_year = $request->month_year;
        // Ngày đầu tháng
        $firstDayOfMonth = Carbon::createFromFormat('Y-m', $month_year)->startOfMonth();

        // Ngày cuối tháng
        $lastDayOfMonth = Carbon::createFromFormat('Y-m', $month_year)->endOfMonth();

        // Tìm tất cả các course để nhóm theo customer_id
        $datas = Course::
        select(
            "customers.id as customers_id",
            "customers.customer_code",
            "customers.closing_date",
            "customers.customer_name",
            "courses.ship_date",
        )
            ->addSelect(\DB::raw('SUM(courses.expressway_fee) as total_courses_expressway_fee'))
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->groupBy("customers.id")
            ->SortByForCourse($request)
            ->whereBetween('courses.ship_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotIn('courses.id', DriverCourse::ALL_ID_SPECIAL)
            ->whereNull('courses.deleted_at');

        $datas = $datas->get()->filter(function ($data) {
            switch ($data['closing_date']){
                case 1:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.1');
                    break;
                case 2:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.2');
                    break;
                case 3:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.3');
                    break;
                case 4:
                    $data['closing_dateName'] = trans('customers.closing_date_lang.4');
                    break;
            };
            return $data;
        });
        return $datas;
    }

    public function create(array $attributes)
    {
        $items = $attributes["items"];
        // Lấy thông tin driver
        $checkDriver_id = $attributes['driver_id'];
        $driver = Driver::find($checkDriver_id);

        // 0.Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start
        foreach ($items as $item) {
            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
                $checkCourse_id = $item['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDateFind = $item['date'];

                $result = array_filter($items, function ($item) use ($checkDateFind) {
                    return $item['date'] === $checkDateFind;
                });
                if (count($result) >1){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.all_id_special_must_one',[
                            "driver_id"=> $driver->id,
                            "driver_name"=> $driver->driver_name,
                            "course_id"=> $item['course_id'],
                            "course_name"=> $course->course_name,
                            "date"=> $checkDateFind,
                        ]));
                }
            }
        }
        // 0.Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó end

        // 1.Kiểm tra trong mảng có đang duplicate không start
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
        // 1.Kiểm tra trong mảng có đang duplicate không end

        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories start
        foreach ($items as $item){
            $checkCourse_id = $item['course_id'];
            $course = Course::find($checkCourse_id);
            $checkDate = $item['date'];
            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');

            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
                ->exists();
            // Nếu có tồn tại (không là duy nhất)
            if ($checkFinalClosingHistories){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.final_closing_histories" ,[
                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name , and date: $checkDate"
                    ]));
            }
        }
        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end

        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
        $driver = Driver::find($checkDriver_id);
        if ($driver->end_date != null){
            // Kiểm tra xem có đã đến qua ngày nghỉ hưu không
            foreach ($items as $item){
                $dateRetirement = Carbon::parse($driver->end_date);
                $checkCourse_id = $item['course_id'];
                $checkDate = Carbon::parse($item['date']);

                $course = Course::find($checkCourse_id);
                if ($dateRetirement->gte($checkDate)){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans("errors.end_date_retirement" ,[
                            "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name",
                            "end_date"=> $dateRetirement->format('Y-m-d')
                        ]));
                }
            }
        }
        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa

        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
        foreach ($items as $item) {
            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
                $checkCourse_id = $item['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDate = $item['date'];

                // Tìm và báo loại bỏ tất cả việc lái xe có ngày hôm đấy
                $checkAllDriverCourseIfSpecial = DriverCourse::
                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
                    ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                    ->where('driver_courses.driver_id', $checkDriver_id)
                    ->where('driver_courses.date', $checkDate)
                    ->whereNull('drivers.end_date') // driver không nghỉ hưu
                    ->first();

                if ($checkAllDriverCourseIfSpecial){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.driver_must_one_course_in_day_with_id_special',[
                            "driver_id"=> $checkAllDriverCourseIfSpecial->driver_id,
                            "driver_name"=> $checkAllDriverCourseIfSpecial->driver_name,
                            "course_id"=> $checkAllDriverCourseIfSpecial->course_id,
                            "course_name"=> $checkAllDriverCourseIfSpecial->course_name,
                            "date"=> $checkDate,
                        ]));
                }
            }
        }
        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định

        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không start
        foreach ($items as $item){
            $checkCourse_id = $item['course_id'];
            $checkDate = $item['date'];

            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
                continue;
            }

            $course = Course::find($checkCourse_id);
            if ($course->ship_date != $checkDate){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.unlike_ship_date" ,[
                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate",
                        "ship_date"=> $course->ship_date
                    ]));
            }
        }
        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không end

        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa start
        foreach ($items as $item){
            $checkCourse_id = $item['course_id'];
            $course = Course::find($checkCourse_id);
            $checkDate = $item['date'];

            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
                continue;
            }

            /*
             * Kiểm tra courses này đã tồn tại trong driver_courses nào chưa
             * và driver_courses phải có drivers.end_date chưa nghỉ hưu
             */
            $checkUnique = DriverCourse::
            join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//                ->where('driver_courses.driver_id', $checkDriver_id)
                ->where('driver_courses.course_id', $checkCourse_id)
//                ->where('driver_courses.date', $checkDate)
                ->whereNull('drivers.end_date') // driver không nghỉ hưu
                ->first();
            // Nếu có driver khác chỉ định rồi thì báo lỗi
            if ($checkUnique){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.has_been_assigned" ,[
                        "attribute"=> "driver_id: $checkUnique->driver_id, driver_name: $driver->driver_name, course_id: $checkUnique->course_id, course_name: $course->course_name and date: $checkUnique->date"
                    ]));
            }
        }
        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa end

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

    public function getDetalDriverCourse($id,$request){
        // Tìm đến tất cả course của driver theo ngày trong request
        $data = $this->model->with("course")
            ->where("driver_id",$id)
            ->where("date",$request->date)->get();

        return ResponseService::responseJson(Response::HTTP_OK, new BaseResource($data));
    }



    public function update_course(array $attributes)
    {
        $items = $attributes["items"];
        $seenIds = [];

        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start
        foreach ($items as $item) {
            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
                //Lấy ra và tìm tất cả driver và date mà có course_id đặc biệt
                $checkDriver_idFind = $item['driver_id'];
                $driver = Driver::find($checkDriver_idFind);
                $checkCourse_id = $item['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDateFind = $item['date'];

                $result = array_filter($items, function ($item) use ($checkDriver_idFind, $checkDateFind) {
                    return $item['driver_id'] === $checkDriver_idFind && $item['date'] === $checkDateFind;
                });
                if (count($result) >1){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.all_id_special_must_one',[
                            "driver_id"=> $item['driver_id'],
                            "driver_name"=> $driver->driver_name,
                            "course_id"=> $item['course_id'],
                            "course_name"=> $course->course_name,
                            "date"=> $checkDateFind,
                        ]));
                }
            }
        }
        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó end

        //1.1 Kiểm tra có trùng update id nào không start
        foreach ($items as $item) {
            if (isset($item['id']) && in_array($item['id'], $seenIds)) {
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans('errors.duplicate_id_shift',[
                        "id"=> $item['id'],
                    ]));
            } else{
                if (isset($item['id'])){
                    $seenIds[] = $item['id'];
                }
            }
        }
        //1.1 Kiểm tra có trùng update id nào không end

        // 1.2 Kiểm tra trong mảng có đang duplicate driver_id và course_id không start
        $uniqueItems = array_map(function ($item) {
            return $item['driver_id'] . '|' . $item['course_id'];
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
                trans('errors.duplicate_driver_id_and_course_id',[
                    "driver_id"=> $duplicates_key_first[0],
                    "course_id"=> $duplicates_key_first[1]
                ]));
        }
        // 1.2 Kiểm tra trong mảng có đang duplicate driver_id không end

        // 1.3 Kiểm tra trong mảng có đang duplicate course_id và date không start
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
        // 1.3 Kiểm tra trong mảng có đang duplicate course_id và date không end

        //1.4 Kiểm tra id course có tồn tại không start
        foreach ($items as $item) {
            if (isset($item['id'])) {
                $driverCourse = DriverCourse::find($item['id']);
                if ($driverCourse == null){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.driver_course_id_not_found',[
                            "id"=> $item['id'],
                        ]));
                }
            }
        }
        //1.4 Kiểm tra id course có tồn tại không end

        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories start
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            $checkCourse_id = $item['course_id'];
            $course = Course::find($checkCourse_id);
            $checkDate = $item['date'];
            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');

            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
                ->exists();
            // Nếu có tồn tại (không là duy nhất)
            if ($checkFinalClosingHistories){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.final_closing_histories" ,[
                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate"
                    ]));
            }
        }
        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end

        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            if ($driver->end_date != null){
                $dateRetirement = Carbon::parse($driver->end_date);
                $checkCourse_id = $item['course_id'];
                $checkDate = Carbon::parse($item['date']);

                $course = Course::find($checkCourse_id);
                if ($dateRetirement->gte($checkDate)){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans("errors.end_date_retirement" ,[
                            "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name",
                            "end_date"=> $dateRetirement->format('Y-m-d')
                        ]));
                }
            }
        }
        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa

        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
        foreach ($items as $item) {
            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
                $checkDriver_id = $item['driver_id'];
                $driver = Driver::find($checkDriver_id);
                $checkCourse_id = $item['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDate = $item['date'];

                // Tìm và báo loại bỏ tất cả việc lái xe có ngày hôm đấy
                $checkAllDriverCourseIfSpecial = DriverCourse::
                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
                    ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                    ->where('driver_courses.driver_id', $checkDriver_id)
                    ->where('driver_courses.date', $checkDate)
                    ->whereNull('drivers.end_date') // driver không nghỉ hưu
                    ->first();
                if ($checkAllDriverCourseIfSpecial){
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.driver_must_one_course_in_day_with_id_special',[
                            "driver_id"=> $checkAllDriverCourseIfSpecial->driver_id,
                            "driver_name"=> $checkAllDriverCourseIfSpecial->driver_name,
                            "course_id"=> $checkAllDriverCourseIfSpecial->course_id,
                            "course_name"=> $checkAllDriverCourseIfSpecial->course_name,
                            "date"=> $checkDate,
                        ]));
                }
            }
        }
        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định

        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không start
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            $checkCourse_id = $item['course_id'];
            $checkDate = $item['date'];

            $course = Course::find($checkCourse_id);
            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
                continue;
            }

            if ($course->ship_date != $checkDate){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.unlike_ship_date" ,[
                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate",
                        "ship_date"=> $course->ship_date
                    ]));
            }
        }
        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không end

        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa start
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            $checkCourse_id = $item['course_id'];
            $course = Course::find($checkCourse_id);
            $checkDate = $item['date'];

            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
                continue;
            }

            /*
             * Kiểm tra courses này đã tồn tại trong driver_courses nào chưa
             * và driver_courses phải có drivers.end_date chưa nghỉ hưu
             */
            $checkUnique = DriverCourse::
            join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//                ->where('driver_courses.driver_id', $checkDriver_id)
                ->where('driver_courses.course_id', $checkCourse_id)
                ->whereNotIn('driver_courses.driver_id', [$checkDriver_id])
//                ->where('driver_courses.date', $checkDate)
                ->whereNull('drivers.end_date') // driver không nghỉ hưu
                ->first();
            // Nếu có driver khác chỉ định rồi thì báo lỗi
            if ($checkUnique){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.has_been_assigned" ,[
                        "attribute"=> "driver_id: $checkUnique->driver_id, driver_name: $checkUnique->driver_name, course_id: $checkUnique->course_id, course_name: $course->course_name and date: $checkUnique->date"
                    ]));
            }
        }
        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa end

        // Lưu lại hoặc update nếu thỏa mãn tất cả điều kiện
        foreach ($items as $item){
            if (isset($item['id'])){
                $driver_courseUpdate = DriverCourse::find($item['id']);
                $driver_courseUpdate->update([
                    "driver_id" => $item['driver_id'],
                    "course_id" => $item['course_id'],
                    "date" => $item['date'],
                    "start_time" => $item['start_time'],
                    "end_time" => $item['end_time'],
                    "break_time" => $item['break_time'],
                    "updated_at" => Carbon::now()
                ]);
            } else{
                $driver_course = new DriverCourse();
                $driver_course->driver_id = $item['driver_id'];
                $driver_course->course_id = $item['course_id'];
                $driver_course->date = $item['date'];
                $driver_course->start_time = $item['start_time'];
                $driver_course->end_time = $item['end_time'];
                $driver_course->break_time = $item['break_time'];
                $driver_course->status = 1;
                $driver_course->save();
            }
            // Cập nhật tiền khách phải trả
            $this->saveCashInStatic($item['course_id'],$item['date']);
        }

        return ResponseService::responseJson(200, new BaseResource($attributes));
    }

    public function saveCashInStatic($course_id,$date){
        // Lưu tiền cash in cho CashInStatical
        $course = Course::find($course_id);
        $customer = Customer::find($course->customer_id);
        /*
         * Truy vấn tổng số tiền driver_code của customer có trong tháng này theo closing_date
         * */
        $closing_dateStart = $this->getClosing_dateStart($customer->closing_date,$date);
        $closing_dateEnd = $this->getClosing_dateEnd($customer->closing_date,$date);
        $driverCourse = DriverCourse::
        select(
            "customers.id as customers_id",
        )
            ->addSelect(\DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->where("customer_id", $course->customer_id)
            ->whereBetween('driver_courses.date', [$closing_dateStart, $closing_dateEnd])
            ->groupBy("customers.id")
            ->first();

        // 1. Kiểm tra xem Customer này đã có CashInStatical chưa
        $cashInStatical = CashInStatical::
        where("customer_id",$course->customer_id)
            ->first();

        // 1.1 Nếu chưa có thì tạo CashInStatical
        if ($cashInStatical == null){
            $month_year = Carbon::parse($date)->format('Y-m');
            CashInStatical::create([
                "customer_id" => $course->customer_id,
                "month_line" => $month_year,
                "balance_previous_month" => 0,
                "receivable_this_month" => 0,
                "total_cash_in_current" => $driverCourse->total_course_ship_fee,
                "status" => 1,
            ]);
        } else{
            // 1.2 Nếu có rồi tìm theo customer_id và date của tháng này theo closing_date
            $checkMonthForThisDate = Carbon::parse($date)->format('Y-m');

            // Nếu có rồi thì update
            // Truy vấn số tiền nợ tháng trước, tìm cho đến khi thấy tháng trước đó
            $cashInStaticalPrevMonth = null;
            $dem = 1;
            do{
                $checkMonth = Carbon::parse($date)->subMonths($dem)->format('Y-m');
                $cashInStaticalPrevMonth = CashInStatical::
                where("customer_id",$course->customer_id)
                    ->where("month_line",$checkMonth)
                    ->first();
                $dem++;
            }while($cashInStaticalPrevMonth !== null);

            $cashInThisDate = CashInStatical::
            where("customer_id",$course->customer_id)
            ->where("month_line",$checkMonthForThisDate)
            ->first();
            // Nếu date chưa có thì tạo
            if ($cashInThisDate == null){
                CashInStatical::create([
                    "customer_id" => $course->customer_id,
                    "month_line" => $checkMonthForThisDate,
                    "balance_previous_month" => $cashInStaticalPrevMonth->total_cash_in_current,
                    "receivable_this_month" => 0,
                    "total_cash_in_current" => $cashInThisDate->balance_previous_month + $driverCourse->total_course_ship_fee - $cashInThisDate->receivable_this_month,
                    "status" => 1,
                ]);
            } else{
                // Cập nhật số tiền sẽ nhận tháng này
                // Tiền tháng này sẽ bằng tiền tháng trước + tổng tiền sẽ nhận tháng này - tổng tiền đã trả
                $cashInThisDate->update([
                'balance_previous_month' => $cashInStaticalPrevMonth->total_cash_in_current,
                'total_cash_in_current' => $cashInThisDate->balance_previous_month + $driverCourse->total_course_ship_fee - $cashInThisDate->receivable_this_month,
            ]);
            }
        }
    }

    public function getClosing_dateStart($closing_date,$date){
        switch ($closing_date){
            case 1:
                $month_year = Carbon::parse($date)->subMonth()->format("Y-m");
                return Carbon::parse($month_year."-16")->format("Y-m-d");
            case 2:
                $month_year = Carbon::parse($date)->subMonth()->format("Y-m");
                return Carbon::parse($month_year."-21")->format("Y-m-d");
            case 3:
                $month_year = Carbon::parse($date)->subMonth()->format("Y-m");
                return Carbon::parse($month_year."-26")->format("Y-m-d");
            case 4:
                $month_year = Carbon::parse($date)->format("Y-m");
                return Carbon::createFromFormat('Y-m', $month_year)->startOfMonth()->format("Y-m-d");
        }
    }

    public function getClosing_dateEnd($closing_date,$date){
        switch ($closing_date){
            case 1:
                $month_year = Carbon::parse($date)->format("Y-m");
                return Carbon::parse($month_year."-15")->format("Y-m-d");
            case 2:
                $month_year = Carbon::parse($date)->format("Y-m");
                return Carbon::parse($month_year."-20")->format("Y-m-d");
            case 3:
                $month_year = Carbon::parse($date)->format("Y-m");
                return Carbon::parse($month_year."-25")->format("Y-m-d");
            case 4:
                $month_year = Carbon::parse($date)->format("Y-m");
                return Carbon::createFromFormat('Y-m', $month_year)->endOfMonth()->format("Y-m-d");
        }
    }

    public function export_shift($request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        ini_set('max_execution_time', '0');
        $dataForListShifts = $this->getAll($request);
        $dataForTotalShiftByClosingDate = $this->totalOfExtraCost($request);
        $getMonth_year = explode("-",$request->month_year);
        $start_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Y-m-d');
        $dataCalendars = $this->calendarRepository->indexGetData($start_date,$end_date);

        $start_dateForNameFile = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Ymd');
        $end_dateForNameFile = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Ymd');

        $inputFileType = 'Xlsx';
        $inputFileName = base_path('resources/excels/ShiftExport.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);

        $sheet = $spreadsheet->getActiveSheet();

        $styleArrayDate = [
            'borders' => [ // Thêm phần borders để thiết lập viền
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FF765E'
                ],
            ],
            'font' => [ // Thêm phần font để thiết lập màu chữ
                'color' => ['rgb' => 'FFFFFF'], // Đây là mã màu trắng
            ],
        ];

        $styleArrayDriver = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFDDC8'
                ],
            ],
        ];

        $styleArrayTotalExtraCost = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'C36150'
                ],
            ],
        ];

        //Nhập khoảng ngày
        $start_dateJapan = Calendar::where("date",$start_date)->first();
        $end_dateJapan = Calendar::where("date",$end_date)->first();

        $start_dateJapanCustomString = Carbon::createFromFormat('Y-m',$request->month_year)->startOfMonth()->format('Y年m月d日')."(".$start_dateJapan['week'].")";
        $end_dateJapanCustomString = Carbon::createFromFormat('Y-m',$request->month_year)->endOfMonth()->format('Y年m月d日')."(".$end_dateJapan['week'].")";

        $aboutDateJapan = $start_dateJapanCustomString."~".$end_dateJapanCustomString;
        $sheet->setCellValue('C1', $aboutDateJapan);

        // tạo khung cho calendar
        $colCalendar = 4;
        $rowCalendar = 3;

        foreach ($dataCalendars as $dataCalendar){
            $getDay = Carbon::parse($dataCalendar['date'])->format('d');

            $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar,intval($getDay)."(".$dataCalendar['week'].")",DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar+1,$dataCalendar['rokuyou'],DataType::TYPE_STRING);
            $colCalendar++;
        }
        $sheet->getStyle([4,3,$colCalendar-1,3])->applyFromArray($styleArrayDate)->getAlignment()->setWrapText(true);
        $sheet->getStyle([4,4,$colCalendar-1,4])->applyFromArray($styleArrayDate)->getAlignment()->setWrapText(true);

        $sheet->mergeCells([$colCalendar,3,$colCalendar,4]);
        $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar,"歩合・食事補助 締日別合計",DataType::TYPE_STRING);
        $sheet->getStyle([$colCalendar,3,$colCalendar,3])->applyFromArray($styleArrayTotalExtraCost)->getAlignment()->setWrapText(true);

        // Truyền dữ liệu tổng vào từng driver

        $styleArrayShiftList = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ];

        // Truyền dữ thông tin từng driver
        $index = 5;
        foreach ($dataForTotalShiftByClosingDate as $key => $value){
            $sheet->setCellValue('A'.$index, $value['driver_code']);
            $sheet->setCellValue('B'.$index, $value['typeName']);
            $sheet->setCellValue('C'.$index, $value['driver_name']);

            // Truyền thông tin course theo ngày cho từng driver
            $colCalendarDriver = 4;

            $driver_id = $value['driver_id'];
            // Kiểm tra từng cột Calendar
            foreach ($dataCalendars as $dataCalendar){
                // Truyền dữ liệu giao hàng, dữ liệu giao hàng nào cùng ngày, driver_id đó thì sẽ nhập
                foreach ($dataForListShifts as $dataForListShift){
                    // Nếu course này cùng driver_id với driver và cùng date với calendar thì truyền giá trị
                    if ($driver_id == $dataForListShift['driver_id'] && $dataCalendar['date'] == $dataForListShift['date']){
                        $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataForListShift['course_names'],DataType::TYPE_STRING);
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$value['total_money'],DataType::TYPE_STRING);

            //Đặt style
            $sheet->getStyle([4,$index,$colCalendarDriver,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);
            // Sau khi kiểm tra xong thì mới được đến driver tiếp
            $index ++;
        }

        $indexCheckStyle = 5;

        foreach ($dataForTotalShiftByClosingDate as $key => $value){
            $sheet->getStyle('A'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
//            dd($sheet->getStyle('D3')->getFill()->getStartColor()->getRGB());
            $sheet->getStyle('B'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
            $sheet->getStyle('C'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
            $indexCheckStyle ++;
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=シフト表_". $start_dateForNameFile."-".$end_dateForNameFile .".xlsx");
        header("Content-Transfer-Encoding: binary ");
        $writer = new Xlsx($spreadsheet);
        ob_get_contents();
        ob_end_clean();
        $writer->save('php://output');
        die();
    }

    public function export_shift_express_charge($request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        ini_set('max_execution_time', '0');
        $dataForListShiftsExpressCharge = $this->getAllExpressCharge($request);
        $dataForTotalShiftExpressCharge = $this->totalOfExpressChargeCost($request);
        $getMonth_year = explode("-",$request->month_year);
        $start_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Y-m-d');
        $dataCalendars = $this->calendarRepository->indexGetData($start_date,$end_date);

        $start_dateForNameFile = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Ymd');
        $end_dateForNameFile = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Ymd');

        $inputFileType = 'Xlsx';
        $inputFileName = base_path('resources/excels/ShiftExportExpressCharge.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);

        $sheet = $spreadsheet->getActiveSheet();

        $styleArrayDate = [
            'borders' => [ // Thêm phần borders để thiết lập viền
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FF765E'
                ],
            ],
            'font' => [ // Thêm phần font để thiết lập màu chữ
                'color' => ['rgb' => 'FFFFFF'], // Đây là mã màu trắng
            ],
        ];

        $styleArrayDriver = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFDDC8'
                ],
            ],
        ];

        $styleArrayTotalExtraCost = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'C36150'
                ],
            ],
        ];

        //Nhập khoảng ngày
        $start_dateJapan = Calendar::where("date",$start_date)->first();
        $end_dateJapan = Calendar::where("date",$end_date)->first();
        $start_dateJapanCustomString = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Y年m月d日')."(".$start_dateJapan['week'].")";
        $end_dateJapanCustomString = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Y年m月d日')."(".$end_dateJapan['week'].")";
        $aboutDateJapan = $start_dateJapanCustomString."~".$end_dateJapanCustomString;
        $sheet->setCellValue('C1', $aboutDateJapan);

        // tạo khung cho calendar
        $colCalendar = 4;
        $rowCalendar = 3;

        foreach ($dataCalendars as $dataCalendar){
            $getDay = Carbon::parse($dataCalendar['date'])->format('d');

            $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar,intval($getDay)."(".$dataCalendar['week'].")",DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar+1,$dataCalendar['rokuyou'],DataType::TYPE_STRING);
            $colCalendar++;
        }
        $sheet->getStyle([4,3,$colCalendar-1,3])->applyFromArray($styleArrayDate)->getAlignment()->setWrapText(true);
        $sheet->getStyle([4,4,$colCalendar-1,4])->applyFromArray($styleArrayDate)->getAlignment()->setWrapText(true);

        $sheet->mergeCells([$colCalendar,3,$colCalendar,4]);
        $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar,"月額合計",DataType::TYPE_STRING);
        $sheet->getStyle([$colCalendar,3,$colCalendar,3])->applyFromArray($styleArrayTotalExtraCost)->getAlignment()->setWrapText(true);

        // Truyền dữ liệu tổng vào từng driver

        $styleArrayShiftList = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ];

        // Truyền dữ thông tin từng driver
        $index = 5;
        foreach ($dataForTotalShiftExpressCharge as $key => $value){
            $sheet->setCellValue('A'.$index, $value['customer_code']);
            $sheet->setCellValue('B'.$index, $value['closing_dateName']);
            $sheet->setCellValue('C'.$index, $value['customer_name']);

            // Truyền thông tin course theo ngày cho từng driver
            $colCalendarDriver = 4;

            $customers_id = $value['customers_id'];
            // Kiểm tra từng cột Calendar
            foreach ($dataCalendars as $dataCalendar){
                // Truyền dữ liệu giao hàng, dữ liệu giao hàng nào cùng ngày, driver_id đó thì sẽ nhập
                foreach ($dataForListShiftsExpressCharge as $dataForListShift){
                    // Nếu course này cùng driver_id với driver và cùng date với calendar thì truyền giá trị
                    if ($customers_id == $dataForListShift['customers_id'] && $dataCalendar['date'] == $dataForListShift['ship_date']){
                        $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataForListShift['courses_expressway_fee'],DataType::TYPE_STRING);
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$value['total_courses_expressway_fee'],DataType::TYPE_STRING);

            //Đặt style
            $sheet->getStyle([4,$index,$colCalendarDriver,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);
            // Sau khi kiểm tra xong thì mới được đến driver tiếp
            $index ++;
        }

        $indexCheckStyle = 5;

        foreach ($dataForTotalShiftExpressCharge as $key => $value){
            $sheet->getStyle('A'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
//            dd($sheet->getStyle('D3')->getFill()->getStartColor()->getRGB());
            $sheet->getStyle('B'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
            $sheet->getStyle('C'.$indexCheckStyle)->applyFromArray($styleArrayDriver)->getAlignment();
            $indexCheckStyle ++;
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=高速代金表_". $start_dateForNameFile."-".$end_dateForNameFile .".xlsx");
        header("Content-Transfer-Encoding: binary ");
        $writer = new Xlsx($spreadsheet);
        ob_get_contents();
        ob_end_clean();
        $writer->save('php://output');
        die();
    }

    public function delete($id)
    {
        $driver_course = $this->model->find($id);

        if ($driver_course == null){
            return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans("errors.not_found"));
        } else{
            // Kiểm tra có nằm trong final_closing_histories
            $month_year = Carbon::parse($driver_course->date)->format("Y-m");
            $final_closing = FinalClosingHistories::where('month_year', $month_year)
                ->exists();
            if ($final_closing){
                return $this->responseJson(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.final_closing_histories',[
                    "attribute"=> $driver_course->date
                ]));
            } else{
                DB::table('driver_courses')->where('id', $id)->delete();
                return $this->responseJson(200, $driver_course, trans('messages.mes.delete_success'));
            }
        }
    }
}
