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
use App\Models\CashIn;
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
use App\Models\CashOutStatistical;
use App\Models\CashOut;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;

class DriverCourseRepository extends BaseRepository implements DriverCourseRepositoryInterface, CalendarRepositoryInterface
{

    public function __construct(Application $app, CalendarRepositoryInterface $calendarRepository, CashOutStatisticalRepositoryInterface $cashOutStatisticalRepository)
    {
        parent::__construct($app);
        $this->calendarRepository = $calendarRepository;
        $this->cashOutStatisticalRepository = $cashOutStatisticalRepository;
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
        $month_year = $request->month_year;

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

        $dataTotalByDriverIds = [];
        if ($request->has('closing_date')){
            $startDate = Carbon::parse($month_year."-".($request->closing_date+1))->subMonth()->format('Y-m-d');
            $endDate = Carbon::parse($month_year."-".$request->closing_date)->format('Y-m-d');
//            $datas->whereBetween('driver_courses.date', [$startDate, $endDate]);

            // Nhóm tất cả những course nằm trong driver
            $dataTotalByDriverIds = $this->model->query()
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
                ->whereNull('driver_courses.deleted_at')->get();
        }

        $datas = $datas->get()->filter(function ($data) {
//            $data->driver->start_date = explode(" ",$data->driver->start_date)[0];
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
            switch ($data['course_names']){
                case "公休":
                    $data['course_names_color'] = trans('courses.color_course.公休');
                    break;
                case "希望休":
                    $data['course_names_color'] = trans('courses.color_course.希望休');
                    break;
                case "有給休暇":
                    $data['course_names_color'] = trans('courses.color_course.有給休暇');
                    break;
                case "半休":
                    $data['course_names_color'] = trans('courses.color_course.半休');
                    break;
                default:
                    $data['course_names_color'] = "";
                    break;
            }
            return $data;
        });

        $groupedDatas = collect($datas)->groupBy('driver_id');

        $listDataConverts = [];
        foreach ($groupedDatas as $checkDatas){
            $dataConverts = [
//                'driver_code' => $checkDatas[0]->driver_code,
                'driver_id' => $checkDatas[0]->driver_id,
//                'driver_name' => $checkDatas[0]->driver_name,
//                'driver_courses_id' => $checkDatas[0]->driver_courses_id,
//                'type' => $checkDatas[0]->type,
//                'typeName' => $checkDatas[0]->typeName,
                'data_by_date' => [],
            ];
            foreach ($checkDatas as $checkData){
                $dataConverts['data_by_date'][] = [
                    "date"=> $checkData['date'],
                    "course_ids"=> $checkData['course_ids'],
                    "course_names"=> $checkData['course_names'],
                    "course_names_color"=> $checkData['course_names_color']
                ];
            }

            $listDataConverts[] = $dataConverts;
        }

        $listDrivers = Driver::query()->SortByForDriver($request)->get()->filter(function ($data) {
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
            }
            return $data;
        });

        $dataConvertForDriver = [];
        foreach ($listDrivers as $driver){
            $driverConvert = [
                'driver_code' => $driver->driver_code,
                'driver_id' => $driver->id,
                'driver_name' => $driver->driver_name,
                'type' => $driver->type,
                'typeName' => $driver->typeName,
                'dataShift' => [],
                'total_money' => 0,
            ];
            foreach ($listDataConverts as $dataConvert){
                if ($driver->id == $dataConvert['driver_id']){
                    $driverConvert['dataShift'] = $dataConvert;
                }
            }
            if (count($dataTotalByDriverIds) != 0){
                foreach ($dataTotalByDriverIds as $dataTotalByDriverId){
                    if($dataTotalByDriverId->driver_id == $driver->id){
                        $driverConvert['total_money'] = $dataTotalByDriverId->total_money;
                    }
                }
            }
            $dataConvertForDriver[] = $driverConvert;
        }

        return $dataConvertForDriver;
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

        //1.4 Kiểm tra id driver_course có tồn tại không start
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
        //1.4 Kiểm tra id driver_course có tồn tại không end

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

        try {
            DB::beginTransaction();
            // Lưu lại hoặc update nếu thỏa mãn tất cả điều kiện
            foreach ($items as $item){
                if (isset($item['id'])){
                    $driver_courseUpdate = DriverCourse::find($item['id']);
                    $course_idBeforeUpdate = $driver_courseUpdate->course_id;
                    $dateBeforeUpdate = $driver_courseUpdate->date;
                    $driver_courseUpdate->update([
                        "driver_id" => $item['driver_id'],
                        "course_id" => $item['course_id'],
                        "date" => $item['date'],
                        "start_time" => $item['start_time'],
                        "end_time" => $item['end_time'],
                        "break_time" => $item['break_time'],
                        "updated_at" => Carbon::now()
                    ]);
                    // Update lại CashInStatic
                    $this->saveCashInStatic($course_idBeforeUpdate,$dateBeforeUpdate);
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
                // $this->saveCashInStatic($item['course_id'],$item['date']);
                $this->saveCashInStatic($item['course_id'],$item['date']);
                // create or update record cash_out_statisticals
                $cashOut = $this->cashOutStatistical($item['driver_id'], $item['date'], $item['course_id']);
            }
            DB::commit();

            return ResponseService::responseJson(200, new BaseResource($attributes));
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function cashOutStatistical($driverId, $date, $courseId)
    {
        // get driver associate
        $arrDriver = Driver::where('type', 4)->get()->pluck('id')->toArray();
        if (!in_array($driverId, $arrDriver)) {
            return true;
        }
        $date = date('Y-m', strtotime($date));
        $cashOutStatisticals = CashOutStatistical::get();
        $arrDriverIdCashOut = $cashOutStatisticals->pluck('driver_id')->toArray();
        $checkDriver = in_array($driverId, $arrDriverIdCashOut);

        $arrMonthCashOut = $cashOutStatisticals->pluck('month_line')->toArray();
        $checkMonth = in_array($date, $arrMonthCashOut);
        // case create:
        // 1: drive = true, month = false
        // 2: drive = false, month = true
        // 3: drive = false, month = false

        // case update:
        // 1: drive = true, month = true

        if ($checkDriver && $checkMonth) {
            // update
            $result = $this->cashOutStatisticalRepository->updateCashOutStatisticalByDriverCourse($driverId, $date, $courseId);
        }
        else {
            // create
            $result = $this->cashOutStatisticalRepository->createCashOutStatisticalByDriverCourse($driverId, $date);
        }

        return $result;
    }

//    public function saveCashInStaticIfUpdate($course_id){
//        // Lấy ra course id cũ để lấy ra tiền và customer_id và closing_date của customer đó
//        $course = Course::find($course_id);
//        $customer = Customer::find($course->customer_id);
//        /*
//        * Truy vấn tổng số tiền driver_code của customer có trong tháng này theo closing_date
//        * */
//        $closing_dateStart = $this->getClosing_dateStart($customer->closing_date,$course->date);
//        $closing_dateEnd = $this->getClosing_dateEnd($customer->closing_date,$course->date);
//
//        // 1. Truy vấn lại tất cả driver_course có date nằm trong closing_date và customer_id để lấy ra tổng số tiền sẽ nhận
//        $driverCourseAfterUpdate = $this->model->
//        select(
//            "customers.id as customers_id",
//        )
//            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
//            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//            ->join('customers', 'customers.id', '=', 'courses.customer_id')
//            ->where("customer_id", $course->customer_id)
//            ->whereBetween('driver_courses.date', [$closing_dateStart, $closing_dateEnd])
//            ->groupBy("customers.id")
//            ->first();
//
//        // 2. Truy vấn lại tất cả cash_in nằm trong closing_date và customer_id để lấy ra số tiền khách trả tháng này
//        $totalCashIn = 0;
//        $totalCashInQuery = CashIn::
//        select("customer_id")
//            ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
//            ->where("customer_id",$course->customer_id)
//            ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
//            ->groupBy("customer_id")
//            ->first();
//        if ($totalCashInQuery != null){
//            $totalCashIn = $totalCashInQuery->total_cash_in;
//        }
//
//        // 3. Truy vấn cash_in_statisticals tháng trước (nếu có) để lấy ra số tiền nợ tháng trước
//        $checkMonthForThisDate = $this->checkClosing_dateForCashInStatical($customer->closing_date,$course->date);
//        $cashInStaticalPrevMonth = CashInStatical::
//        where("customer_id",$course->customer_id)
//            ->where("month_line","<",$checkMonthForThisDate)
//            ->orderBy("month_line", "desc")
//            ->first();
//
//        $moneyBalance_previous_month = 0;
//        if($cashInStaticalPrevMonth){
//            $moneyBalance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
//        }
//
//        // Truy vấn cash_in_statisticals cần cập nhật theo date(lấy ra month_year theo closing_date) và customer_id
//
//    }

    public function saveCashInStatic($course_id,$date){
        // Lưu tiền cash in cho CashInStatical
        $course = Course::find($course_id);
        $customer = Customer::find($course->customer_id);
        /*
         * Truy vấn tổng số tiền driver_code của customer có trong tháng này theo closing_date
         * */
        $closing_dateStart = $this->getClosing_dateStart($customer->closing_date,$date);
        $closing_dateEnd = $this->getClosing_dateEnd($customer->closing_date,$date);

        $driverCourse = $this->model->
        select(
            "customers.id as customers_id",
        )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->where("customer_id", $course->customer_id)
            ->whereBetween('driver_courses.date', [$closing_dateStart, $closing_dateEnd])
            ->groupBy("customers.id")
            ->first();

        $receivable_this_month = 0;
        if ($driverCourse != null){
            $receivable_this_month = $driverCourse->total_course_ship_fee;
        }

        // Truy vấn tổng tất cả cash-in của customer đó có trong khoảng closing date để cập nhật tiền cash-in-statics
        $totalCashIn = 0;
        $totalCashInQuery = CashIn::
        select("customer_id")
            ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
            ->where("customer_id",$course->customer_id)
            ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
            ->groupBy("customer_id")
            ->first();
        if ($totalCashInQuery != null){
            $totalCashIn = $totalCashInQuery->total_cash_in;
        }

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
                "balance_previous_month" => 0, // tiền nhận tháng trước
                "receivable_this_month" => $receivable_this_month, // tiền phải nhận tháng này
                "total_cash_in_current" => $receivable_this_month - $totalCashIn,
                "status" => 1,
            ]);

        } else{
            // 1.2 Nếu có rồi tìm theo customer_id và date của tháng này theo closing_date
            $checkMonthForThisDate = $this->checkClosing_dateForCashInStatical($customer->closing_date,$date);

            // Nếu có rồi thì update
            // Truy vấn số tiền nợ tháng gần nhất bằng cách tìm đến toàn bộ CashInStatical theo thời gian
            // Kiểm tra xem Customer này đã có CashInStatical tháng này chưa
//            $cashInStaticalMonthLines = CashInStatical::
//            where("customer_id",$course->customer_id)
//                ->whereNotIn("month_line",[$checkMonthForThisDate])
//                ->get()->pluck('month_line')->toArray();

            $cashInStaticalPrevMonth = CashInStatical::
            where("customer_id",$course->customer_id)
                ->where("month_line","<",$checkMonthForThisDate)
                ->orderBy("month_line", "desc")
                ->first();

            $balance_previous_month = 0;
            if($cashInStaticalPrevMonth){
                $balance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
            }
//            if (count($cashInStaticalMonthLines) !== 0){
//                $targetTimestamp = strtotime($checkMonthForThisDate);
//
//                $closestTimestamp = null;
//                $closestDiff = PHP_INT_MAX;
//
//                foreach ($cashInStaticalMonthLines as $timestamp) {
//                    $check = $targetTimestamp - $timestamp;
//                    if ($check < 0){
//                        continue;
//                    }
//
//                    if ($check < $closestDiff) {
//                        $closestDiff = $check;
//                        $closestTimestamp = $timestamp;
//                    }
//                }
//
//                $closestDate = date('Y-m', $closestTimestamp);
//
//                // Lấy ra số tiền tháng trước
//                $cashInStaticalPrevMonth = CashInStatical::
//                where("customer_id",$course->customer_id)
//                    ->where("month_line",$closestDate)
//                    ->first();
//                $moneyBalance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
//            }

            $cashInThisDate = CashInStatical::
            where("customer_id",$course->customer_id)
            ->where("month_line",$checkMonthForThisDate)
            ->first();

            // Nếu chưa có thì tạo
            if ($cashInThisDate == null){
                CashInStatical::create([
                    "customer_id" => $course->customer_id,
                    "month_line" => $checkMonthForThisDate,
                    "balance_previous_month" => $balance_previous_month, // tiền nhận tháng trước
                    "receivable_this_month" => $receivable_this_month, // tiền tổng tiền phải nhận tháng này
                    "total_cash_in_current" => $balance_previous_month + $receivable_this_month - $totalCashIn,
                    "status" => 1,
                ]);
            } else{
                // Cập nhật số tiền sẽ nhận tháng này
                // Tiền tháng này sẽ bằng tiền tháng trước + tổng tiền sẽ nhận tháng này - tổng tiền đã trả
                $cashInThisDate->update([
                    'balance_previous_month' => $balance_previous_month, // tiền tháng trước
                    "receivable_this_month" => $receivable_this_month, // tiền phải nhận tháng này
                    'total_cash_in_current' => $balance_previous_month + $receivable_this_month - $totalCashIn,
                ]);
                // Kiểm tra xem có tháng các tháng còn lại không để cập nhật
                $checkCashInStaticalUpdates = CashInStatical::
                where("customer_id",$course->customer_id)
                    ->where("month_line",">",$checkMonthForThisDate)
                    ->orderBy("month_line", "asc")
                    ->get();
                // Nếu có thì cập nhật các bản ghi còn lại
                $update_balance_previous_month = $balance_previous_month;
                if (count($checkCashInStaticalUpdates) != 0){
                    foreach ($checkCashInStaticalUpdates as $cashInStaticalUpdate){
                        // 1. Truy vấn tổng số tiền trong theo từng tháng theo closing date
                        // 1.1 Lấy ra khoảng thời gian theo closing_date
                        $updateStartDateByClosingDate = "";
                        $updateEndDateByClosingDate = "";
                        switch ($customer->closing_date){
                            case 1:
                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."16";
                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."15";
                                break;
                            case 2:
                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."21";
                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."20";
                                break;
                            case 3:
                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."26";
                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."25";
                                break;
                            case 4:
                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->startOfMonth();
                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->endOfMonth();
                                break;
                        }
                        // 1.2 truy vấn tổng số tiền cần nhận trong tháng cần update lại theo closing date
                        $updateByDriverCourseTotal = DriverCourse::
                        select(
                            "customers.id as customers_id",
                        )
                            ->addSelect(\DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
                            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                            ->join('customers', 'customers.id', '=', 'courses.customer_id')
                            ->where("customer_id", $course->customer_id)
                            ->whereBetween('driver_courses.date', [$updateStartDateByClosingDate, $updateEndDateByClosingDate])
                            ->groupBy("customers.id")
                            ->first();

                        // 1.3 truy vấn tổng số tiền phải nhận trong tháng cần update lại theo closing date
                        $updateByTotalCashIn = 0;
                        $totalCashInQuery = CashIn::
                        select("customer_id")
                            ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
                            ->where("customer_id",$course->customer_id)
                            ->whereBetween('payment_date', [$updateStartDateByClosingDate,$updateEndDateByClosingDate])
                            ->groupBy("customer_id")
                            ->first();
                        if ($totalCashInQuery != null){
                            $updateByTotalCashIn = $totalCashInQuery->total_cash_in;
                        }

                        // 1.4 Update lại tiền
                        $cashInStaticalUpdate->update([
                            'balance_previous_month' => $update_balance_previous_month, // tiền tháng trước
                            "receivable_this_month" => $updateByDriverCourseTotal, // tiền phải nhận tháng này
                            'total_cash_in_current' => $update_balance_previous_month + $updateByDriverCourseTotal - $updateByTotalCashIn,
                        ]);
                        $update_balance_previous_month = $update_balance_previous_month + $updateByDriverCourseTotal - $updateByTotalCashIn;
                    }
                }
            }
        }
    }

    public function checkClosing_dateForCashInStatical($closing_date,$date){
        switch ($closing_date){
            case 1:
                // 15
                // So sánh date với ngày 16 tháng trước và 15 tháng này
                return $this->getClosing_dateMonth($date,"-16","-15");
            case 2:
                // 20
                // So sánh date với ngày 21 tháng trước và 20 tháng này
                return $this->getClosing_dateMonth($date,"-21","-20");
            case 3:
                // 25
                // So sánh date với ngày 26 tháng trước và 25 tháng này
                return $this->getClosing_dateMonth($date,"-26","-25");
            case 4:
                // cuối tháng
                return Carbon::parse($date)->format("Y-m");
        }
    }

    public function getClosing_dateMonth($date,$closingDateStart,$closingDateEnd){
        // So sánh date với ngày 16 tháng trước và 15 tháng này
        $thisMonth_year = Carbon::parse($date)->format("Y-m");
        $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
        $checkDate = Carbon::parse($date);
        $dateThisMonth = Carbon::parse($thisMonth_year.$closingDateEnd);
        $datePrevMonth = Carbon::parse($prevMonth_year.$closingDateStart);
        // Nếu qua ngày 15 tháng này
        if ($checkDate->gt($dateThisMonth)){
            // Lấy tháng sau
            return Carbon::parse($date)->addMonth()->format("Y-m");
        }
        // Nếu nằm trong khoảng ngày 16 tháng trước và 15 tháng này
        if ($checkDate->gte($datePrevMonth) && $checkDate->lte($dateThisMonth)){
            // Lấy tháng này
            return Carbon::parse($date)->format("Y-m");
        }
    }

    public function getClosing_dateStart($closing_date,$payment_date){
        switch ($closing_date){
            case 1:
                // So sánh date với ngày 16 tháng trước và 15 tháng này
                return $this->parseByClosingDateStart($payment_date,"-16","-15");
            case 2:
                // So sánh date với ngày 21 tháng trước và 20 tháng này
                return $this->parseByClosingDateStart($payment_date,"-21","-20");
            case 3:
                // So sánh date với ngày 26 tháng trước và 25 tháng này
                return $this->parseByClosingDateStart($payment_date,"-26","-25");
            case 4:
                $month_year = Carbon::parse($payment_date)->format("Y-m");
                return Carbon::createFromFormat('Y-m', $month_year)->startOfMonth()->format("Y-m-d");
        }
    }

    public function parseByClosingDateStart($date,$closingDateStart,$closingDateEnd){
        $thisMonth_year = Carbon::parse($date)->format("Y-m");
        $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
        $checkDate = Carbon::parse($date);
        $date15ThisMonth = Carbon::parse($thisMonth_year.$closingDateEnd);
        $date16PrevMonth = Carbon::parse($prevMonth_year.$closingDateStart);
        // Nếu qua ngày 15 tháng này VD: 16/8-15/9
        if ($checkDate->gt($date15ThisMonth)){
            // Lấy tháng sau
            return Carbon::parse($checkDate)->format("Y-m").$closingDateStart;
        }
        // Nếu nằm trong khoảng ngày 16 tháng trước và 15 tháng này VD: 16/7 - 15/8
        if ($checkDate->gte($date16PrevMonth) && $checkDate->lte($date15ThisMonth)){
            // Lấy tháng này
            return Carbon::parse($checkDate)->subMonth()->format("Y-m").$closingDateStart;
        }
//        // Nếu trước ngày 16 tháng trước VD: 16/7 - 15/8
//        if ($checkDate->lt($date16PrevMonth)){
//            // Lấy tháng trước
//            return Carbon::parse($checkDate)->subMonth()->format("Y-m").$closingDateStart;
//        }
    }

    public function getClosing_dateEnd($closing_date,$payment_date){
        switch ($closing_date){
            case 1:
                // So sánh date với ngày 16 tháng trước và 15 tháng này
                return $this->parseByClosingDateEnd($payment_date,"-16","-15");
            case 2:
                // So sánh date với ngày 21 tháng trước và 20 tháng này
                return $this->parseByClosingDateEnd($payment_date,"-21","-20");
            case 3:
                // So sánh date với ngày 26 tháng trước và 25 tháng này
                return $this->parseByClosingDateEnd($payment_date,"-26","-25");
            case 4:
                $month_year = Carbon::parse($payment_date)->format("Y-m");
                return Carbon::createFromFormat('Y-m', $month_year)->endOfMonth()->format("Y-m-d");
        }
    }

    public function parseByClosingDateEnd($date,$closingDateStart,$closingDateEnd){
        $thisMonth_year = Carbon::parse($date)->format("Y-m");
        $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
        $checkDate = Carbon::parse($date);
        $date15ThisMonth = Carbon::parse($thisMonth_year.$closingDateEnd);
        $date16PrevMonth = Carbon::parse($prevMonth_year.$closingDateStart);
        // Nếu qua ngày 15 tháng này VD: 16/8-15/9
        if ($checkDate->gt($date15ThisMonth)){
            // Lấy tháng sau
            return Carbon::parse($checkDate)->addMonth()->format("Y-m").$closingDateEnd;
        }
        // Nếu nằm trong khoảng ngày 16 tháng trước và 15 tháng này VD: 16/7 - 15/8
        if ($checkDate->gte($date16PrevMonth) && $checkDate->lte($date15ThisMonth)){
            // Lấy tháng này
            return Carbon::parse($checkDate)->format("Y-m").$closingDateEnd;
        }
//        // Nếu trước ngày 16 tháng trước VD: 16/6 - 15/7
//        if ($checkDate->lt($date16PrevMonth)){
//            // Lấy tháng trước
//            return Carbon::parse($checkDate)->subMonth()->format("Y-m").$closingDateEnd;
//        }
    }

    public function export_shift($request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        ini_set('max_execution_time', '0');
        $dataForListShifts = $this->getAll($request);
//        $dataForTotalShiftByClosingDate = $this->totalOfExtraCost($request);
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
        foreach ($dataForListShifts as $key => $value){
            $sheet->setCellValue('A'.$index, $value['driver_code']);
            $sheet->setCellValue('B'.$index, $value['typeName']);
            $sheet->setCellValue('C'.$index, $value['driver_name']);

            // Truyền thông tin course theo ngày cho từng driver
            $colCalendarDriver = 4;

            // Kiểm tra từng cột Calendar
            foreach ($dataCalendars as $dataCalendar){
                // Truyền dữ liệu giao hàng, dữ liệu giao hàng nào cùng ngày, driver_id đó thì sẽ nhập
                foreach ($value['dataShift']['data_by_date'] as $dataByDate){
                    // Nếu course này cùng driver_id với driver và cùng date với calendar thì truyền giá trị
                    if ($dataCalendar['date'] == $dataByDate['date']){
                        $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataByDate['course_names'],DataType::TYPE_STRING);
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            if ($value['total_money'] != 0){
                $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$value['total_money'],DataType::TYPE_STRING);
            }

            //Đặt style
            $sheet->getStyle([4,$index,$colCalendarDriver,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);
            // Sau khi kiểm tra xong thì mới được đến driver tiếp
            $index ++;
        }

        $indexCheckStyle = 5;

        foreach ($dataForListShifts as $key => $value){
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
