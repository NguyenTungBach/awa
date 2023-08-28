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
use App\Repositories\Contracts\CashInStaticalRepositoryInterface;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
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

    public function __construct(Application $app, CalendarRepositoryInterface $calendarRepository, CashOutStatisticalRepositoryInterface $cashOutStatisticalRepository, CashInStaticalRepositoryInterface $cashInStaticalRepository)
    {
        parent::__construct($app);
        $this->calendarRepository = $calendarRepository;
        $this->cashOutStatisticalRepository = $cashOutStatisticalRepository;
        $this->cashInStaticalRepository = $cashInStaticalRepository;
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

    public function detailEditShift($request){
        // Gọi đến tất cả
        $getMonth_year = explode("-",$request->month_year);

        // Tìm tất cả những course nằm trong driver
        $datas = $this->model->query()
            ->select(
                "driver_courses.id as driver_courses_id",
                "driver_courses.start_time as start_time",
                "driver_courses.break_time as break_time",
                "driver_courses.end_time as end_time",
                "driver_courses.driver_id",
                "driver_courses.course_id as course_id",
                "driver_courses.date",
                "drivers.driver_name",
                "drivers.driver_code",
                "drivers.type",
            )
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->SortByForDriverCourse($request)
            ->whereYear("driver_courses.date",$getMonth_year[0])
            ->whereMonth("driver_courses.date",$getMonth_year[1])
            ->whereNull('driver_courses.deleted_at')->get();
        $groupedDatas = collect($datas)->groupBy('driver_id');
        $dataCustom =[
            'items'=>[]
        ];
        foreach ($groupedDatas as $checkDatas){
            $dataConverts = [
                'driver_id' => $checkDatas[0]->driver_id,
                'listShift'=> []
            ];
            foreach ($checkDatas as $checkData){
                $dataConverts['listShift'][] = [
                    'course_id' => $checkData->course_id,
                    'date' => $checkData->date,
                    'start_time' => Carbon::createFromFormat('H:i:s', $checkData->start_time)->format('H:i'),
                    'break_time' => Carbon::createFromFormat('H:i:s', $checkData->break_time)->format('H:i'),
                    'end_time' => Carbon::createFromFormat('H:i:s', $checkData->end_time)->format('H:i'),
                ];
            }
            $dataCustom['items'][]=$dataConverts;
        }

        return $dataCustom;
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

            // Tổng tiền những course nằm trong driver
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

        // Lấy toàn bộ cho tháng này
        $startDateCalendar = Carbon::parse($month_year)->startOfMonth()->format('Y-m-d');
        $endDateCalendar = Carbon::parse($month_year)->endOfMonth()->format('Y-m-d');
        $calendars = Calendar::whereBetween('date', [$startDateCalendar, $endDateCalendar])->get();

        $listDataConverts = [];
        foreach ($groupedDatas as $checkDatas){
            $dataConverts = [
                'driver_id' => $checkDatas[0]->driver_id,
                'data_by_date' => [],
            ];

            // Kiểm tra mỗi calendar
            foreach ($calendars as $calendar){
                $dataByCalendar = [
                    'driver_id' => $checkDatas[0]->driver_id,
                    "date"=> $calendar->date,
                    "course_ids"=> "",
                    "course_names"=> "",
                    "course_names_color"=> ""
                ];
                // Chỉ lấy ra driver có lịch trình
                foreach ($checkDatas as $checkData){
                    if ($calendar->date == $checkData['date']){
                        $dataByCalendar = [
                            'driver_id' => $checkDatas[0]->driver_id,
                            "date"=> $checkData['date'],
                            "course_ids"=> $checkData['course_ids'],
                            "course_names"=> $checkData['course_names'],
                            "course_names_color"=> $checkData['course_names_color']
                        ];
                        break;
                    }
                }
                $dataConverts['data_by_date'][] = $dataByCalendar;
            }
            $listDataConverts[] = $dataConverts;
        }

        // Tìm tất cả driver còn làm việc (trong tháng đó) hoặc những driver <= tháng nghỉ hưu
        $getMonth_year = explode("-",$request->month_year); // Dành cho trường hợp kiểm tra nghỉ hưu
        $listDrivers = Driver::query()
            ->whereRaw("DATE_FORMAT(start_date,'%Y-%m') <= ?",[$request->month_year])
            ->whereNull('end_date') // Tìm những driver chưa nghỉ hưu kể từ ngày bắt đầu tức start_date phải rơi vào hoặc là quá khứ năm tháng đó
            ->orWhere(function ($query) use ($getMonth_year) {
                $query->whereYear('end_date', $getMonth_year[0])
                    ->whereMonth('end_date',"<=", $getMonth_year[1]);
            })
            ->SortByForDriver($request)->get()->filter(function ($data) {
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
                'total_money' => '',
            ];
            foreach ($listDataConverts as $dataConvert){
                // Nếu driver đó có lịch trình thì gán vào còn không thì để ngày lịch rỗng
                if ($driver->id == $dataConvert['driver_id']){
                    $driverConvert['dataShift'] = $dataConvert;
                    break;
                } else{
                    $driverConvert['dataShift'] = [
                        'driver_id' => $driver->id,
                        'data_by_date' => [],
                    ];
                    foreach ($calendars as $calendar){
                        $driverConvert['dataShift']['data_by_date'][] = [
                            "driver_id" => $driver->id,
                            "date"=> $calendar->date,
                            "course_ids"=> "",
                            "course_names"=> "",
                            "course_names_color"=> ""
                        ];
                    }
                }
            }
            if (count($driverConvert['dataShift']) == 0){
                $driverConvert['dataShift'] = [
                    'driver_id' => $driver->id,
                    'data_by_date' => [],
                ];
                foreach ($calendars as $calendar){
                    $driverConvert['dataShift']['data_by_date'][] = [
                        "driver_id" => $driver->id,
                        "date"=> $calendar->date,
                        "course_ids"=> "",
                        "course_names"=> "",
                        "course_names_color"=> ""
                    ];
                }
            }
            if (count($dataTotalByDriverIds) != 0){
                foreach ($dataTotalByDriverIds as $dataTotalByDriverId){
                    if($dataTotalByDriverId->driver_id == $driver->id){
                        $driverConvert['total_money'] = $dataTotalByDriverId->total_money;
                        break;
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
                "customers.id as customer_id",
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

        $datas = $datas->get();

        // Tìm tất cả các course để nhóm theo customer_id
        $dataTotal = Course::
        select(
            "customers.id as customer_id",
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
            ->whereNull('courses.deleted_at')->get();

        $groupedDatas = collect($datas)->groupBy('customer_id');

        // Lấy toàn bộ cho tháng này
        $startDateCalendar = Carbon::parse($month_year)->startOfMonth()->format('Y-m-d');
        $endDateCalendar = Carbon::parse($month_year)->endOfMonth()->format('Y-m-d');
        $calendars = Calendar::whereBetween('date', [$startDateCalendar, $endDateCalendar])->get();

        $listDataConverts = [];
        foreach ($groupedDatas as $checkDatas){
            $dataConverts = [
                'customer_id' => $checkDatas[0]->customer_id,
                'customer_code' => $checkDatas[0]->customer_code,
                'customer_name' => $checkDatas[0]->customer_name,
                'closing_date' => $checkDatas[0]->closing_date,
                'data_ship_date' => [],
            ];

            // Kiểm tra mỗi calendar cho mỗi customer
            foreach ($calendars as $calendar){
                // Check lịch trình cho mỗi customer
                $dataByCalendar = [
                    "ship_date"=> $calendar->date,
                    "courses_expressway_fee"=> "",
                ];
                // Tìm xem ngày đó có trùng không
                foreach ($checkDatas as $checkData){
                    // Nếu đúng ngày này có tồn tại
                    if ($calendar->date == $checkData['ship_date']){
                        $dataByCalendar = [
                            "ship_date"=> $checkData['ship_date'],
                            "courses_expressway_fee"=> $checkData['courses_expressway_fee'],
                        ];
                        break;
                    }
                }
                $dataConverts['data_ship_date'][] = $dataByCalendar;
            }
            $listDataConverts[] = $dataConverts;
        }

        $listCustomer = Customer::query()->SortByForCustomer($request)->get()->filter(function ($data) {
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

        $dataConvertForCustomer = [];
        foreach ($listCustomer as $customer){
            $driverConvert = [
                'customer_code' => $customer->customer_code,
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'closing_date' => $customer->closing_date,
                'closing_dateName' => $customer->closing_dateName,
                'dataShiftExpress' => [],
                'total_courses_expressway_fee' => '',
            ];
            foreach ($listDataConverts as $dataConvert){
                if ($customer->id == $dataConvert['customer_id']){
                    $driverConvert['dataShiftExpress'] = $dataConvert;
                }
            }
            if (count($driverConvert['dataShiftExpress']) == 0){
                $driverConvert['dataShiftExpress'] = [
                    'customer_id' => $customer->id,
                    'customer_code' => $customer->customer_code,
                    'customer_name' => $customer->customer_name,
                    'closing_date' => $customer->closing_date,
                    'data_ship_date' => [],
                ];
                foreach ($calendars as $calendar){
                    $driverConvert['dataShiftExpress']['data_ship_date'][] = [
                        "ship_date"=> $calendar->date,
                        "courses_expressway_fee"=> "",
                    ];
                }
            }
            if (count($dataTotal) != 0){
                foreach ($dataTotal as $dataTotalByCustomerId){
                    if($dataTotalByCustomerId->customer_id == $customer->id){
                        $driverConvert['total_courses_expressway_fee'] = $dataTotalByCustomerId->total_courses_expressway_fee;
                        break;
                    }
                }
            }
            $dataConvertForDriver[] = $driverConvert;
        }


        return $dataConvertForDriver;
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

//    public function create(array $attributes)
//    {
//        $items = $attributes["items"];
//        // Lấy thông tin driver
//        $checkDriver_id = $attributes['driver_id'];
//        $driver = Driver::find($checkDriver_id);
//
//        // 0.Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start
//        foreach ($items as $item) {
//            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
//                $checkCourse_id = $item['course_id'];
//                $course = Course::find($checkCourse_id);
//                $checkDateFind = $item['date'];
//
//                $result = array_filter($items, function ($item) use ($checkDateFind) {
//                    return $item['date'] === $checkDateFind;
//                });
//                if (count($result) >1){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans('errors.all_id_special_must_one',[
//                            "driver_id"=> $driver->id,
//                            "driver_name"=> $driver->driver_name,
//                            "course_id"=> $item['course_id'],
//                            "course_name"=> $course->course_name,
//                            "date"=> $checkDateFind,
//                        ]));
//                }
//            }
//        }
//        // 0.Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó end
//
//        // 1.Kiểm tra trong mảng có đang duplicate không start
//        $uniqueItems = array_map(function ($item) {
//            return $item['course_id'] . '|' . $item['date'];
//        }, $items);
//        $countedItems = array_count_values($uniqueItems);
//
//        // Lấy ra
//        $duplicates = array_filter($countedItems, function ($count) {
//            return $count > 1;
//        });
//        if (!empty($duplicates)) {
//            $duplicates_key_first = explode('|',array_key_first($duplicates));
////            $duplicates_value_first = $duplicates[$duplicates_key_first];
//            return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                trans('errors.duplicate_course_id_and_date',[
//                    "course_id"=> $duplicates_key_first[0],
//                    "date"=> $duplicates_key_first[1]
//                ]));
//        }
//        // 1.Kiểm tra trong mảng có đang duplicate không end
//
//        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories start
//        foreach ($items as $item){
//            $checkCourse_id = $item['course_id'];
//            $course = Course::find($checkCourse_id);
//            $checkDate = $item['date'];
//            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');
//
//            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
//                ->exists();
//            // Nếu có tồn tại (không là duy nhất)
//            if ($checkFinalClosingHistories){
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans("errors.final_closing_histories" ,[
//                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name , and date: $checkDate"
//                    ]));
//            }
//        }
//        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end
//
//        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
//        $driver = Driver::find($checkDriver_id);
//        if ($driver->end_date != null){
//            // Kiểm tra xem có đã đến qua ngày nghỉ hưu không
//            foreach ($items as $item){
//                $dateRetirement = Carbon::parse($driver->end_date);
//                $checkCourse_id = $item['course_id'];
//                $checkDate = Carbon::parse($item['date']);
//
//                $course = Course::find($checkCourse_id);
//                if ($dateRetirement->gte($checkDate)){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans("errors.end_date_retirement" ,[
//                            "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name",
//                            "end_date"=> $dateRetirement->format('Y-m-d')
//                        ]));
//                }
//            }
//        }
//        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
//
//        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
//        foreach ($items as $item) {
//            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
//                $checkCourse_id = $item['course_id'];
//                $course = Course::find($checkCourse_id);
//                $checkDate = $item['date'];
//
//                // Tìm và báo loại bỏ tất cả việc lái xe có ngày hôm đấy
//                $checkAllDriverCourseIfSpecial = DriverCourse::
//                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//                    ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//                    ->where('driver_courses.driver_id', $checkDriver_id)
//                    ->where('driver_courses.date', $checkDate)
//                    ->whereNull('drivers.end_date') // driver không nghỉ hưu
//                    ->first();
//
//                if ($checkAllDriverCourseIfSpecial){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans('errors.driver_must_one_course_in_day_with_id_special',[
//                            "driver_id"=> $checkAllDriverCourseIfSpecial->driver_id,
//                            "driver_name"=> $checkAllDriverCourseIfSpecial->driver_name,
//                            "course_id"=> $checkAllDriverCourseIfSpecial->course_id,
//                            "course_name"=> $checkAllDriverCourseIfSpecial->course_name,
//                            "date"=> $checkDate,
//                        ]));
//                }
//            }
//        }
//        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
//
//        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không start
//        foreach ($items as $item){
//            $checkCourse_id = $item['course_id'];
//            $checkDate = $item['date'];
//
//            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
//            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
//                continue;
//            }
//
//            $course = Course::find($checkCourse_id);
//            if ($course->ship_date != $checkDate){
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans("errors.unlike_ship_date" ,[
//                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate",
//                        "ship_date"=> $course->ship_date
//                    ]));
//            }
//        }
//        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không end
//
//        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa start
//        foreach ($items as $item){
//            $checkCourse_id = $item['course_id'];
//            $course = Course::find($checkCourse_id);
//            $checkDate = $item['date'];
//
//            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
//            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
//                continue;
//            }
//
//            /*
//             * Kiểm tra courses này đã tồn tại trong driver_courses nào chưa
//             * và driver_courses phải có drivers.end_date chưa nghỉ hưu
//             */
//            $checkUnique = DriverCourse::
//            join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
////                ->where('driver_courses.driver_id', $checkDriver_id)
//                ->where('driver_courses.course_id', $checkCourse_id)
////                ->where('driver_courses.date', $checkDate)
//                ->whereNull('drivers.end_date') // driver không nghỉ hưu
//                ->first();
//            // Nếu có driver khác chỉ định rồi thì báo lỗi
//            if ($checkUnique){
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans("errors.has_been_assigned" ,[
//                        "attribute"=> "driver_id: $checkUnique->driver_id, driver_name: $driver->driver_name, course_id: $checkUnique->course_id, course_name: $course->course_name and date: $checkUnique->date"
//                    ]));
//            }
//        }
//        // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa end
//
//        // Lưu lại nếu thỏa mãn tất cả điều kiện
//        foreach ($items as $item){
//            $driver_course = new DriverCourse();
//            $driver_course->driver_id = $attributes['driver_id'];
//            $driver_course->course_id = $item['course_id'];
//            $driver_course->date = $item['date'];
//            $driver_course->start_time = $item['start_time'];
//            $driver_course->end_time = $item['end_time'];
//            $driver_course->break_time = $item['break_time'];
//            $driver_course->status = 1;
//            $driver_course->save();
//        }
//
//        return ResponseService::responseJson(200, new BaseResource($attributes));
//    }

    public function getDetalDriverCourse($driver_id,$request){
        // Tìm đến tất cả course của driver theo ngày trong request
        $driver_courses = $this->model->with("course")
            ->where("driver_id",$driver_id)
            ->where("date",$request->date)->get()->filter(function ($data) {
                $data->start_time = Carbon::createFromFormat('H:i:s', $data->start_time)->format('H:i');
                $data->break_time = Carbon::createFromFormat('H:i:s', $data->break_time)->format('H:i');
                $data->end_time = Carbon::createFromFormat('H:i:s', $data->end_time)->format('H:i');
                return $data;
            });

        $data = [
            "driver_id"=>$driver_id,
            "date"=>$request->date,
            "listShift"=>$driver_courses
        ];

        return ResponseService::responseJson(Response::HTTP_OK, new BaseResource($data));
    }

    public function update_course(array $attributes)
    {
        $items = $attributes["items"];
        $seenIds = [];
//        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start
//        foreach ($items as $item) {
//            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
//                //Lấy ra và tìm tất cả driver và date mà có course_id đặc biệt
//                $checkDriver_idFind = $item['driver_id'];
//                $driver = Driver::find($checkDriver_idFind);
//                $checkCourse_id = $item['course_id'];
//                $course = Course::find($checkCourse_id);
//                $checkDateFind = $item['date'];
//
//                $result = array_filter($items, function ($item) use ($checkDriver_idFind, $checkDateFind) {
//                    return $item['driver_id'] === $checkDriver_idFind && $item['date'] === $checkDateFind;
//                });
//                if (count($result) >1){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans('errors.all_id_special_must_one',[
//                            "driver_id"=> $item['driver_id'],
//                            "driver_name"=> $driver->driver_name,
//                            "course_id"=> $item['course_id'],
//                            "course_name"=> $course->course_name,
//                            "date"=> $checkDateFind,
//                        ]));
//                }
//            }
//        }
//        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó end
//
//        //1.1 Kiểm tra có trùng update id nào không start
//        foreach ($items as $item) {
//            if (isset($item['id']) && in_array($item['id'], $seenIds)) {
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans('errors.duplicate_id_shift',[
//                        "id"=> $item['id'],
//                    ]));
//            } else{
//                if (isset($item['id'])){
//                    $seenIds[] = $item['id'];
//                }
//            }
//        }
//        //1.1 Kiểm tra có trùng update id nào không end
//
//        // 1.2 Kiểm tra trong mảng có đang duplicate driver_id và course_id không start
//        $uniqueItems = array_map(function ($item) {
//            return $item['driver_id'] . '|' . $item['course_id'];
//        }, $items);
//        $countedItems = array_count_values($uniqueItems);
//
//        // Lấy ra
//        $duplicates = array_filter($countedItems, function ($count) {
//            return $count > 1;
//        });
//        if (!empty($duplicates)) {
//            $duplicates_key_first = explode('|',array_key_first($duplicates));
////            $duplicates_value_first = $duplicates[$duplicates_key_first];
//            return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                trans('errors.duplicate_driver_id_and_course_id',[
//                    "driver_id"=> $duplicates_key_first[0],
//                    "course_id"=> $duplicates_key_first[1]
//                ]));
//        }
//        // 1.2 Kiểm tra trong mảng có đang duplicate driver_id và course_id không end
//
//        // 1.3 Kiểm tra trong mảng có đang duplicate course_id và date không start
//        $uniqueItems = array_map(function ($item) {
//            return $item['course_id'] . '|' . $item['date'];
//        }, $items);
//        $countedItems = array_count_values($uniqueItems);
//
//        // Lấy ra
//        $duplicates = array_filter($countedItems, function ($count) {
//            return $count > 1;
//        });
//        if (!empty($duplicates)) {
//            $duplicates_key_first = explode('|',array_key_first($duplicates));
////            $duplicates_value_first = $duplicates[$duplicates_key_first];
//            return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                trans('errors.duplicate_course_id_and_date',[
//                    "course_id"=> $duplicates_key_first[0],
//                    "date"=> $duplicates_key_first[1]
//                ]));
//        }
//        // 1.3 Kiểm tra trong mảng có đang duplicate course_id và date không end
//
//        //1.4 Kiểm tra id driver_course có tồn tại không start
//        foreach ($items as $item) {
//            if (isset($item['id'])) {
//                $driverCourse = DriverCourse::find($item['id']);
//                if ($driverCourse == null){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans('errors.driver_course_id_not_found',[
//                            "id"=> $item['id'],
//                        ]));
//                }
//            }
//        }
//        //1.4 Kiểm tra id driver_course có tồn tại không end
//
//        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories start
//        foreach ($items as $item){
//            $checkDriver_id = $item['driver_id'];
//            $driver = Driver::find($checkDriver_id);
//            $checkCourse_id = $item['course_id'];
//            $course = Course::find($checkCourse_id);
//            $checkDate = $item['date'];
//            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');
//
//            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
//                ->exists();
//            // Nếu có tồn tại (không là duy nhất)
//            if ($checkFinalClosingHistories){
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans("errors.final_closing_histories" ,[
//                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate"
//                    ]));
//            }
//        }
//        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end
//
//        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
//        foreach ($items as $item){
//            $checkDriver_id = $item['driver_id'];
//            $driver = Driver::find($checkDriver_id);
//            if ($driver->end_date != null){
//                $dateRetirement = Carbon::parse($driver->end_date);
//                $checkCourse_id = $item['course_id'];
//                $checkDate = Carbon::parse($item['date']);
//
//                $course = Course::find($checkCourse_id);
//                if ($dateRetirement->gte($checkDate)){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans("errors.end_date_retirement" ,[
//                            "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name",
//                            "end_date"=> $dateRetirement->format('Y-m-d')
//                        ]));
//                }
//            }
//        }
//        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
//
//        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
//        foreach ($items as $item) {
//            if (in_array($item['course_id'], DriverCourse::ALL_ID_SPECIAL)) {
//                $checkDriver_id = $item['driver_id'];
//                $driver = Driver::find($checkDriver_id);
//                $checkCourse_id = $item['course_id'];
//                $course = Course::find($checkCourse_id);
//                $checkDate = $item['date'];
//
//                // Tìm và báo loại bỏ tất cả việc lái xe có ngày hôm đấy
//                $checkAllDriverCourseIfSpecial = DriverCourse::
//                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//                    ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//                    ->where('driver_courses.driver_id', $checkDriver_id)
//                    ->where('driver_courses.date', $checkDate)
//                    ->whereNull('drivers.end_date') // driver không nghỉ hưu
//                    ->first();
//                if ($checkAllDriverCourseIfSpecial){
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans('errors.driver_must_one_course_in_day_with_id_special',[
//                            "driver_id"=> $checkAllDriverCourseIfSpecial->driver_id,
//                            "driver_name"=> $checkAllDriverCourseIfSpecial->driver_name,
//                            "course_id"=> $checkAllDriverCourseIfSpecial->course_id,
//                            "course_name"=> $checkAllDriverCourseIfSpecial->course_name,
//                            "date"=> $checkDate,
//                        ]));
//                }
//            }
//        }
//        // 4.Kiểm tra tất cả ngày hôm đấy lái xe có đang được chỉ định gì không nếu có thì yêu cầu xóa các chỉ định
//
//        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không start
//        foreach ($items as $item){
//            $checkDriver_id = $item['driver_id'];
//            $driver = Driver::find($checkDriver_id);
//            $checkCourse_id = $item['course_id'];
//            $checkDate = $item['date'];
//
//            $course = Course::find($checkCourse_id);
//            // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
//            if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
//                continue;
//            }
//
//            if ($course->ship_date != $checkDate){
//                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                    trans("errors.unlike_ship_date" ,[
//                        "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name, and date: $checkDate",
//                        "ship_date"=> $course->ship_date
//                    ]));
//            }
//        }
//        // 5.Kiểm tra ngày chọn có đúng như trong ship_date của courses không end

        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start
        foreach ($items as $item) {
            foreach ($item['listShift'] as $shift){
                // Trong trường hợp thấy id đặc biệt thì danh sách chỉ được mỗi id ngày đó thôi
                if (in_array($shift['course_id'], DriverCourse::ALL_ID_SPECIAL)){
                    $idCourseSPECIAL = $shift['course_id'];
                    $checkDateFind = Carbon::parse($shift['date'])->format('Y-m-d'); // Ngày cần check
                    //Quay về Kiểm tra lại xem trong lịch trình ngày đó chỉ được một nếu còn tồn tại ngày khác thì báo lỗi
                    $filterDate = array_filter($item['listShift'], function ($item) use ($checkDateFind) {
                        return $item['date'] === $checkDateFind;
                    });

                    // Nếu lịch trình lớn hơn 1 thì báo lỗi
                    if (count($filterDate) >1){
                        $driver = Driver::find($item['driver_id']);
                        $course = Course::find($idCourseSPECIAL);
                        $checkDateFind = Carbon::parse($shift['date'])->format('Y-m-d');

                        return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                            trans('errors.all_id_special_must_one',[
                                "driver_id"=> $item['driver_id'],
                                "driver_name"=> $driver->driver_name,
                                "course_id"=> $idCourseSPECIAL,
                                "course_name"=> $course->course_name,
                                "date"=> $checkDateFind,
                            ]));
                    }
                }
            }
        }
        // 1.0 Kiểm tra nếu có id đặc biệt thì driver chỉ định ngày đó thì tất cả items chỉ có mỗi id đó start

        // 1.1 Kiểm tra các driver_id có bị trùng nhau không
        $seenDriverIds = [];
        foreach ($items as $item) {
            if (in_array($item['driver_id'], $seenDriverIds)){
                $driver = Driver::find($item['driver_id']);
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans('errors.duplicate_driver_id',[
                        "driver_id"=> $item['driver_id'],
                        "driver_name"=> $driver->driver_name,
                    ]));
            }
            $seenDriverIds[] = $item['driver_id'];
        }
        // 1.1 Kiểm tra các driver_id có bị trùng nhau không

        // 1.2 Kiểm tra các driver_id có bị trùng lịch không
        $seenCourseIds = [];
        foreach ($items as $item) {
            foreach ($item['listShift'] as $shift) {
                $courseId = $shift['course_id'];

                // Bỏ qua trường hợp nếu course này nằm trong danh sách ngày đặc biệt
                // Trường hợp course id trùng cho phép duplicate
                if (in_array($courseId, DriverCourse::ALL_ID_SPECIAL)){
                    continue;
                }

                // Nếu course_id này có nằm trong danh sách courseId thì báo lỗi
                if (in_array($courseId, $seenCourseIds)) {
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.duplicate_driver_id_and_course_id',[
                            "driver_id"=> $item['driver_id'],
                            "course_id"=> $courseId
                        ]));
                }

                $seenCourseIds[] = $courseId;
            }
        }
        // 1.2 Kiểm tra các driver_id có bị trùng lịch không

        // 1.3 Kiểm tra các ngày course truyền vào có nằm trong tháng không không
        foreach ($items as $item) {
            foreach ($item['listShift'] as $shift) {
                $courseId = $shift['course_id'];
                $checkDate = Carbon::parse($shift['date'])->format("Y-m");

                // Bỏ qua trường hợp nếu course này nằm trong danh sách ngày đặc biệt
                // Trường hợp course id trùng cho phép duplicate
                if (in_array($courseId, DriverCourse::ALL_ID_SPECIAL)){
                    continue;
                }

                // Nếu nếu course này không nằm trong tháng thì báo lỗi
                if ($checkDate != $attributes['month_year']) {
                    $driver = Driver::find($item['driver_id']);
                    $course = Course::find($courseId);
                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.date_not_in_month',[
                            "driver_id"=> $item['driver_id'],
                            "driver_name"=> $driver->driver_name,
                            "course_id"=> $courseId,
                            "course_name"=> $course->course_name,
                            "month_year"=> $attributes['month_year']
                        ]));
                }
            }
        }
        // 1.3 Kiểm tra các driver_id có bị trùng lịch không

        // 2. Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories start
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            foreach ($item['listShift'] as $shift) {
                $checkCourse_id = $shift['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDate = $shift['date'];
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
        }
        // 2.Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end

        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            if ($driver->end_date != null){
                foreach ($item['listShift'] as $shift){
                    $checkCourse_id = $shift['course_id'];
                    $course = Course::find($checkCourse_id);
                    $dateRetirement = Carbon::parse($driver->end_date);
                    $checkDate = Carbon::parse($shift['date']);
                    // Nếu ngày chọn đã đến hoặc qua ngày nghỉ hưu thì báo lỗi
                    if ($checkDate->gte($dateRetirement)){
                        return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                            trans("errors.end_date_retirement" ,[
                                "attribute"=> "driver_id: $checkDriver_id, driver_name: $driver->driver_name, course_id: $checkCourse_id, course_name: $course->course_name",
                                "end_date"=> $dateRetirement->format('Y-m-d')
                            ]));
                    }
                }
            }
        }
        // 3.Kiểm tra xem lái xe đó đã nghỉ hưu chưa

        // 4.Kiểm tra ngày chọn có đúng như trong ship_date của courses không start
        foreach ($items as $item){
            $checkDriver_id = $item['driver_id'];
            $driver = Driver::find($checkDriver_id);
            foreach ($item['listShift'] as $shift){
                $checkCourse_id = $shift['course_id'];
                $course = Course::find($checkCourse_id);
                $checkDate = $shift['date'];

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
        }
        // 4.Kiểm tra ngày chọn có đúng như trong ship_date của courses không end

        try {
            DB::beginTransaction();
//            // Xóa các driver_course theo id và cập nhật lại Cash In
//            if (isset($attributes["delete_shifts"])){
//                if (count($attributes['delete_shifts']) != 0){
//                    $this->deleteAll($attributes['delete_shifts']);
//                }
//            }

//            // Nếu tồn tại items thì mới update
//            $items = $attributes["items"];
//            // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa start
//            foreach ($items as $item){
//                $checkDriver_id = $item['driver_id'];
//                $driver = Driver::find($checkDriver_id);
//                $checkCourse_id = $item['course_id'];
//                $course = Course::find($checkCourse_id);
//                $checkDate = $item['date'];
//
//                // Nếu trường hợp course_id nằm trong id đặc biệt thì bỏ qua
//                if (in_array($checkCourse_id, DriverCourse::ALL_ID_SPECIAL)){
//                    continue;
//                }
//
//                /*
//                 * Kiểm tra courses này đã tồn tại trong driver_courses nào chưa
//                 * và driver_courses phải có drivers.end_date chưa nghỉ hưu
//                 */
//                $checkUnique = DriverCourse::
//                join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//                    ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
////                ->where('driver_courses.driver_id', $checkDriver_id)
//                    ->where('driver_courses.course_id', $checkCourse_id)
////                    ->whereNotIn('driver_courses.driver_id', [$checkDriver_id])
////                ->where('driver_courses.date', $checkDate)
//                    ->whereNull('drivers.end_date') // driver không nghỉ hưu
//                    ->first();
//                // Nếu có driver khác chỉ định rồi thì báo lỗi
//                if ($checkUnique){
//                    DB::rollBack(); // Roll back lại toàn bộ không cho xóa
//                    return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
//                        trans("errors.has_been_assigned" ,[
//                            "attribute"=> "driver_id: $checkUnique->driver_id, driver_name: $checkUnique->driver_name, course_id: $checkUnique->course_id, course_name: $course->course_name and date: $checkUnique->date"
//                        ]));
//                }
//            }
//            // 6.Kiểm tra trong mảng corse này đã được driver nào khác chỉ định chưa end

            // Lưu lại nếu thỏa mãn tất cả điều kiện
            // Xóa tất cả các driver_course trong nằm trong tháng năm đó
            $getMonth_year = explode("-",$attributes['month_year']);
            DB::table('driver_courses')
                ->whereYear("date",$getMonth_year[0])
                ->whereMonth("date",$getMonth_year[1])
                ->delete();

            foreach ($items as $item){
                foreach ($item['listShift'] as $shift){
                    $driver_course = new DriverCourse();
                    $driver_course->driver_id = $item['driver_id'];
                    $driver_course->course_id = $shift['course_id'];
                    $driver_course->date = $shift['date'];
                    $driver_course->start_time = $shift['start_time'];
                    $driver_course->end_time = $shift['end_time'];
                    $driver_course->break_time = $shift['break_time'];
                    $driver_course->status = 1;
                    $driver_course->save();

                    // Nếu không nằm trong id đặc biệt (không phải khách) thì được phép cần cập nhật
                    if (!in_array($shift['course_id'], DriverCourse::ALL_ID_SPECIAL)){
                        // Cập nhật tiền khách phải trả
                        $course = Course::find($shift['course_id']);
                        $this->cashInStaticalRepository->saveCashInStatic($course->customer_id,$shift['date']);
                    }
                    // create or update record cash_out_statisticals
                    $cashOut = $this->cashOutStatistical($item['driver_id'], $shift['date'], $shift['course_id']);
                }
//                if (isset($item['id'])){
//                    $driver_courseUpdate = DriverCourse::find($item['id']);
//                    $courseBeforeUpdate = Course::find($driver_courseUpdate->course_id);
//                    $dateBeforeUpdate = $driver_courseUpdate->date;
//                    $driver_courseUpdate->update([
//                        "driver_id" => $item['driver_id'],
//                        "course_id" => $item['course_id'],
//                        "date" => $item['date'],
//                        "start_time" => $item['start_time'],
//                        "end_time" => $item['end_time'],
//                        "break_time" => $item['break_time'],
//                        "updated_at" => Carbon::now()
//                    ]);
//                    // Update lại CashInStatic
//                    // Nếu không nằm trong id đặc biệt (không phải khách) thì được phép cần cập nhật
//                    if (!in_array($courseBeforeUpdate->id, DriverCourse::ALL_ID_SPECIAL)){
//                        $this->cashInStaticalRepository->saveCashInStatic($courseBeforeUpdate->customer_id,$dateBeforeUpdate);
//                    }
//                } else{
//                    $driver_course = new DriverCourse();
//                    $driver_course->driver_id = $item['driver_id'];
//                    $driver_course->course_id = $item['course_id'];
//                    $driver_course->date = $item['date'];
//                    $driver_course->start_time = $item['start_time'];
//                    $driver_course->end_time = $item['end_time'];
//                    $driver_course->break_time = $item['break_time'];
//                    $driver_course->status = 1;
//                    $driver_course->save();
//                }
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

        $cashOutStatisticals = CashOutStatistical::where('driver_id', $driverId)->where('month_line', $date)->first();
        if (empty($cashOutStatisticals)) {
            $result = $this->cashOutStatisticalRepository->createCashOutStatisticalByDriverCourse($driverId, $date);
        } else {
            $result = $this->cashOutStatisticalRepository->updateCashOutStatisticalByDriverCourse($driverId, $date, $courseId);
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
                if ($value['dataShift'] != null){
                    // Truyền dữ liệu giao hàng, dữ liệu giao hàng nào cùng ngày, driver_id đó thì sẽ nhập
                    foreach ($value['dataShift']['data_by_date'] as $dataByDate){
                        // Nếu course này cùng driver_id với driver và cùng date với calendar thì truyền giá trị
                        if ($dataCalendar['date'] == $dataByDate['date']){
                            $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataByDate['course_names'],DataType::TYPE_STRING);
                        }
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            if ($value['total_money'] != ""){
                $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,number_format($value['total_money']),DataType::TYPE_STRING);
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
//        $dataForTotalShiftExpressCharge = $this->totalOfExpressChargeCost($request);
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
        foreach ($dataForListShiftsExpressCharge as $key => $value){
            $sheet->setCellValue('A'.$index, $value['customer_code']);
            $sheet->setCellValue('B'.$index, $value['closing_dateName']);
            $sheet->setCellValue('C'.$index, $value['customer_name']);

            // Truyền thông tin course theo ngày cho từng driver
            $colCalendarDriver = 4;
            // Kiểm tra từng cột Calendar
            foreach ($dataCalendars as $dataCalendar){
                if ($value['dataShiftExpress'] != null){
                    // Truyền dữ liệu giao hàng
                    foreach ($value['dataShiftExpress']['data_ship_date'] as $dataForListShift){
                        // Nếu course này cùng date với calendar thì truyền giá trị
                        if ($dataCalendar['date'] == $dataForListShift['ship_date']){
                            $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataForListShift['courses_expressway_fee'] == '' ? '' : number_format($dataForListShift['courses_expressway_fee']),DataType::TYPE_STRING);
                        }
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            if ($value['total_courses_expressway_fee'] != ""){
                $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,number_format($value['total_courses_expressway_fee']),DataType::TYPE_STRING);
            }

            //Đặt style
            $sheet->getStyle([4,$index,$colCalendarDriver,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);
            // Sau khi kiểm tra xong thì mới được đến driver tiếp
            $index ++;
        }

        $indexCheckStyle = 5;

        foreach ($dataForListShiftsExpressCharge as $key => $value){
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

    public function deleteAll($deleteShifts)
    {
        // Kiểm tra ids cho từng Shift
        foreach ($deleteShifts as $infoDeleteShift){
            //1. Kiểm tra xem shift này có tồn tại không
            $deleteShift = DriverCourse::find($infoDeleteShift["shift_id"]);
            if ($deleteShift == null){
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.attribute_not_found',[
                    "attribute"=> "driver_course",
                    "id"=> $infoDeleteShift["shift_id"]
                ]));
            }
            //2. Kiểm tra xem driver_id này có tồn tại không
            $driver = Driver::find($infoDeleteShift["driver_id"]);
            if ($driver == null){
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.attribute_not_found',[
                    "attribute"=> "driver",
                    "id"=> $infoDeleteShift["driver_id"]
                ]));
            }

            //3. Kiểm tra có được phép xóa không, xem trong bảng final_closing_histories start
            $driver = Driver::find($infoDeleteShift["driver_id"]);
            $course = Course::find($deleteShift->course_id);
            $checkDate = $course->ship_date;
            $getMonthYear = Carbon::parse($checkDate)->format('Y-m');

            $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
                ->exists();
            // Nếu có tồn tại (không là duy nhất)
            if ($checkFinalClosingHistories){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.shift_not_delete_in_final_closing_histories" ,[
                        "attribute"=> "driver_id: $driver, driver_name: $driver->driver_name, course_id: $deleteShift->course_id, course_name: $course->course_name, and date: $checkDate"
                    ]));
            }
            //3. Kiểm tra có được phép tạo không, xem trong bảng final_closing_histories end
        }

        try {
            DB::beginTransaction();
            // Nếu thỏa mãn các điều kiện trên thì tiến hành xóa và update lại Cash In static
            foreach ($deleteShifts as $infoDeleteShift){
                // Lấy ra Shift cần xóa
                $deleteShift = DriverCourse::find($infoDeleteShift["shift_id"]);

                // Nếu course nằm trong id đặc biệt thì chỉ cần xóa và không cần cập nhật Cash In Static
                if (in_array($deleteShift->course_id, DriverCourse::ALL_ID_SPECIAL)){
                    DB::table('driver_courses')->where('id', $infoDeleteShift["shift_id"])->delete();
                } else{
                    // Lấy ra course để customer lấy ra customer trước khi xóa để cập nhật Cash In static
                    $course = Course::find($deleteShift->course_id);

                    DB::table('driver_courses')->where('id', $infoDeleteShift["shift_id"])->delete();
                    // Sau khi xóa hoàn toàn thì cập nhật lại Cash In static
                    $this->cashInStaticalRepository->saveCashInStatic($course->customer_id,$deleteShift->date);
                }

                // Phần của Yến
            }
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }

    }

    public function salesList($request){
        // Truy vấn toàn bộ customer
        $customers = Customer::query()->SortByForCustomer($request)->get()->filter(function ($data) {
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

        // Lấy ra đầu và cuối tháng này
        $fistDayMonth = Carbon::parse($request->month_year)->startOfMonth()->format("Y-m-d");
        $lastDayMonth = Carbon::parse($request->month_year)->endOfMonth()->format("Y-m-d");

        // Lấy toàn bộ cho tháng này
        $calendars = Calendar::whereBetween('date', [$fistDayMonth, $lastDayMonth])->get();


        // Truy vấn Tổng toàn bộ DriverCourse từng customer theo tháng và theo closing date
        $dataListSales = [];
        $totalAllDataListSalesByClosingDate = "";
        $demTotalAllDataListSalesByClosingDate = 0;
        foreach ($customers as $customer){
            $dataConvert = [
                "customer_id" =>$customer->id,
                "customer_code" =>$customer->customer_code,
                "closing_date" =>$customer->closing_date,
                "closing_dateName" =>$customer->closing_dateName,
                "customer_name" =>$customer->customer_name,
                "date_ship_fee" =>null,
                "total_ship_fee_by_closing_date" => "",
                "total_ship_fee_by_month" => "",
            ];

            // Truy vấn toàn bộ DriverCourse và trong tháng
            $driverCourseMonthQueries = DriverCourse::
            select(
                "courses.customer_id as courses_customer_id",
                "courses.ship_fee as courses_ship_fee",
                "driver_courses.date",
            )
                ->addSelect(DB::raw('SUM(courses.ship_fee) as courses_ship_fee'))
                ->where("courses.customer_id",$customer->id)
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                ->join('customers', 'customers.id', '=', 'courses.customer_id')
                ->groupBy("courses.customer_id","driver_courses.date")
                ->whereBetween('driver_courses.date', [$fistDayMonth, $lastDayMonth])
                ->get();

            foreach ($calendars as $calendar){
                $dataByCalendar = [
                    "courses_customer_id"=> $customer->id,
                    "courses_ship_fee" => "",
                    "date" => $calendar->date
                ];

                foreach ($driverCourseMonthQueries as $driverCourseMonthQuery){
                    if ($calendar->date == $driverCourseMonthQuery->date){
                        $dataByCalendar = $driverCourseMonthQuery;
                        break;
                    }
                }
                $dataConvert['date_ship_fee'][] = $dataByCalendar;
            }

            $startDateByClosingDate = $this->cashInStaticalRepository->getClosingDateByMonthStart($customer->closing_date,$request->month_year);
            $endDateByClosingDate = $this->cashInStaticalRepository->getClosingDateByMonthEnd($customer->closing_date,$request->month_year);
            // Truy vấn Tổng toàn bộ CashIn từng customer theo closing date
            $driverCourseClosingDateQuery = DriverCourse::
            select(
                "courses.customer_id as courses_customer_id",
                "driver_courses.date",
            )
                ->addSelect(DB::raw('SUM(courses.ship_fee) as total_ship_fee_by_closing_date'))
                ->where("courses.customer_id",$customer->id)
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                ->join('customers', 'customers.id', '=', 'courses.customer_id')
                ->whereBetween('driver_courses.date', [$startDateByClosingDate, $endDateByClosingDate])
                ->groupBy("courses.customer_id")
                ->first();
            if ($driverCourseClosingDateQuery){
                $dataConvert['total_ship_fee_by_closing_date'] = $driverCourseClosingDateQuery->total_ship_fee_by_closing_date;
                $demTotalAllDataListSalesByClosingDate = $demTotalAllDataListSalesByClosingDate + $driverCourseClosingDateQuery->total_ship_fee_by_closing_date;
            }

            // Truy vấn Tổng toàn bộ CashIn từng customer theo tháng
            $cashInTotalMonthQuery = DriverCourse::
            select(
                "courses.customer_id as courses_customer_id",
                "driver_courses.date",
            )
                ->addSelect(DB::raw('SUM(courses.ship_fee) as total_ship_fee_by_month'))
                ->where("courses.customer_id",$customer->id)
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                ->join('customers', 'customers.id', '=', 'courses.customer_id')
                ->whereBetween('driver_courses.date', [$fistDayMonth, $lastDayMonth])
                ->groupBy("courses.customer_id")
                ->first();

            if ($cashInTotalMonthQuery){
                $dataConvert['total_ship_fee_by_month'] = $cashInTotalMonthQuery->total_ship_fee_by_month;
            }

            $dataListSales[] = $dataConvert;
        }

        if ($demTotalAllDataListSalesByClosingDate != 0){
            $totalAllDataListSalesByClosingDate = $demTotalAllDataListSalesByClosingDate;
        }

        // Tổng số tiền tất cả customer theo ngày
        $totalAllDataListSalesByDate = [];
        foreach ($calendars as $calendar){
            // Truy vấn tổng số tiền ship_fee theo ngày
            $dataByCalendar = [
                "courses_customer_id"=> $customer->id,
                "total_all_ship_fee_by_date" => "",
                "date" => $calendar->date
            ];

            // Truy vấn toàn bộ driver_course trong ngày
            $totalSaleByDate = DriverCourse::
            select(
                "courses.customer_id as courses_customer_id",
                "driver_courses.date",
            )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_all_ship_fee_by_date'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->where('date',$calendar->date)
            ->groupBy("driver_courses.date")
            ->first();

            if ($totalSaleByDate){
                $dataByCalendar['total_all_ship_fee_by_date'] = $totalSaleByDate->total_all_ship_fee_by_date;
            }

            $totalAllDataListSalesByDate[] = $dataByCalendar;
        }

        // Tổng số tiền customer theo tháng
        $totalAllDataListSalesByMonth = "";
        $getMonth_year = explode("-",$request->month_year);
        $totalAllSalesByMonth = DriverCourse::
        select(
            "driver_courses.date",
        )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_all_ship_fee_by_month'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->whereYear('date',$getMonth_year[0])
            ->whereMonth('date',$getMonth_year[1])
            ->first();
        if ($totalAllSalesByMonth){
            $totalAllDataListSalesByMonth = $totalAllSalesByMonth->total_all_ship_fee_by_month;
        }

        $data = [
            'data'=> $dataListSales,
            'total_all_sales_by_date'=> $totalAllDataListSalesByDate,
            'total_all_data_sales_by_closing_date'=> $totalAllDataListSalesByClosingDate,
            'total_all_data_sales_by_month'=> $totalAllDataListSalesByMonth,
        ];


        return $data;
    }

    public function exportSaleList($request){
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        ini_set('max_execution_time', '0');
        $dataForListSales = $this->salesList($request);
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
        $sheet->mergeCells([$colCalendar+1,3,$colCalendar+1,4]);
        $sheet->setCellValueExplicitByColumnAndRow($colCalendar, $rowCalendar,"月額合計",DataType::TYPE_STRING);
        $sheet->setCellValueExplicitByColumnAndRow($colCalendar+1, $rowCalendar,"締日別合計",DataType::TYPE_STRING);
        $sheet->getStyle([$colCalendar,3,$colCalendar,3])->applyFromArray($styleArrayTotalExtraCost)->getAlignment()->setWrapText(true);
        $sheet->getStyle([$colCalendar+1,3,$colCalendar+1,3])->applyFromArray($styleArrayTotalExtraCost)->getAlignment()->setWrapText(true);

        // Truyền dữ liệu tổng vào từng driver
        $styleArrayShiftList = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ];

        // Truyền dữ thông tin từng driver
        $index = 5;
        foreach ($dataForListSales['data'] as $key => $value){
            $sheet->setCellValue('A'.$index, $value['customer_code']);
            $sheet->setCellValue('B'.$index, $value['closing_dateName']);
            $sheet->setCellValue('C'.$index, $value['customer_name']);

            // Truyền thông tin course theo ngày cho từng driver
            $colCalendarDriver = 4;

            // Kiểm tra từng cột Calendar
            foreach ($dataCalendars as $dataCalendar){
                if ($value['date_ship_fee'] != null){
                    // Truyền dữ liệu giao hàng, dữ liệu giao hàng nào cùng ngày, driver_id đó thì sẽ nhập
                    foreach ($value['date_ship_fee'] as $dataByDate){
                        // Nếu course này cùng driver_id với driver và cùng date với calendar thì truyền giá trị
                        if ($dataCalendar['date'] == $dataByDate['date']){
                            $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$dataByDate['courses_ship_fee'] == '' ? '' : number_format($dataByDate['courses_ship_fee']),DataType::TYPE_STRING);
                        }
                    }
                }
                $colCalendarDriver++;
            }
            //Truyền dữ liệu tổng vào
            if ($value['total_ship_fee_by_closing_date'] != ""){
                $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver, $index,$value['total_ship_fee_by_closing_date'] == '' ? '' : number_format($value['total_ship_fee_by_closing_date']),DataType::TYPE_STRING);
            }
            if ($value['total_ship_fee_by_month'] != ""){
                $sheet->setCellValueExplicitByColumnAndRow($colCalendarDriver+1, $index,$value['total_ship_fee_by_month'] == '' ? '' : number_format($value['total_ship_fee_by_month']),DataType::TYPE_STRING);
            }

            //Đặt style
            $sheet->getStyle([4,$index,$colCalendarDriver+1,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);
            // Sau khi kiểm tra xong thì mới được đến driver tiếp
            $index ++;
        }
        $sheet->setCellValue('A'.$index, "日別合計");
        $sheet->mergeCells([1,$index,3,$index]);
        $sheet->getStyle('A'.$index)->applyFromArray($styleArrayDriver)->getAlignment()->setWrapText(true);
        // Truyền dữ liệu tổng cho từng ngày và tháng
        $colCalendarTotal = 4;
        // Kiểm tra từng cột Calendar
        foreach ($dataCalendars as $dataCalendar){
            foreach ($dataForListSales['total_all_sales_by_date'] as $key => $value){
                // Nếu trùng ngày thì truyền vào
                if ($dataCalendar['date'] == $value['date']){
                    $sheet->setCellValueExplicitByColumnAndRow($colCalendarTotal, $index,$value['total_all_ship_fee_by_date'] == '' || $value['total_all_ship_fee_by_date'] == "0" ? '' : number_format($value['total_all_ship_fee_by_date']),DataType::TYPE_STRING);
                    break;
                }
            }
            $colCalendarTotal ++;
        }
        // Cập nhật nốt tổng closing date
        $sheet->setCellValueExplicitByColumnAndRow($colCalendarTotal, $index,$dataForListSales['total_all_data_sales_by_closing_date'] == '' || $dataForListSales['total_all_data_sales_by_closing_date'] == "0" ? '' : number_format($dataForListSales['total_all_data_sales_by_closing_date']),DataType::TYPE_STRING);
        // Cập nhật nốt tổng month
        $sheet->setCellValueExplicitByColumnAndRow($colCalendarTotal+1, $index,$dataForListSales['total_all_data_sales_by_month'] == '' ? '' : number_format($dataForListSales['total_all_data_sales_by_month']),DataType::TYPE_STRING);
        //Đặt style
        $sheet->getStyle([4,$index,$colCalendarTotal+1,$index])->applyFromArray($styleArrayShiftList)->getAlignment()->setWrapText(true);

        $indexCheckStyle = 5;

        foreach ($dataForListSales['data'] as $key => $value){
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
        header("Content-Disposition: attachment;filename=売上金額表_". $start_dateForNameFile."-".$end_dateForNameFile .".xlsx");
        header("Content-Transfer-Encoding: binary ");
        $writer = new Xlsx($spreadsheet);
        ob_get_contents();
        ob_end_clean();
        $writer->save('php://output');
        die();
    }

    public function saleDetailByClosingDate($request,$id){
        // Truy vấn customer theo id
        $customer = Customer::find($id);
        switch ($customer['closing_date']){
            case 1:
                $customer['closing_dateName'] = trans('customers.closing_date_lang.1');
                break;
            case 2:
                $customer['closing_dateName'] = trans('customers.closing_date_lang.2');
                break;
            case 3:
                $customer['closing_dateName'] = trans('customers.closing_date_lang.3');
                break;
            case 4:
                $customer['closing_dateName'] = trans('customers.closing_date_lang.4');
                break;
        }

        // Lấy ra đầu và cuối tháng này
        $fistDayMonth = Carbon::parse($request->month_year)->startOfMonth()->format("Y-m-d");
        $lastDayMonth = Carbon::parse($request->month_year)->endOfMonth()->format("Y-m-d");

        $startDateByClosingDate = $this->cashInStaticalRepository->getClosingDateByMonthStart($customer->closing_date,$request->month_year);
        $endDateByClosingDate = $this->cashInStaticalRepository->getClosingDateByMonthEnd($customer->closing_date,$request->month_year);

        // Lấy khoảng ngày theo tiếng nhật
        $start_dateClosingDateJapan = Calendar::where("date",$startDateByClosingDate)->first();
        $end_dateClosingDateJapan = Calendar::where("date",$endDateByClosingDate)->first();

        $start_dateJapanCustomString = Carbon::parse($startDateByClosingDate)->format('Y年m月d日')."(".$start_dateClosingDateJapan['week'].")";
        $end_dateJapanCustomString = Carbon::parse($endDateByClosingDate)->format('Y年m月d日')."(".$end_dateClosingDateJapan['week'].")";
        $invoice_date = Carbon::now()->format('Y年m月d日')." "."発行";

        $aboutDateJapan = "'".$start_dateJapanCustomString."~"."'".$end_dateJapanCustomString;

        // Truy vấn Tổng toàn bộ DriverCourse từng customer theo tháng và theo closing date
        $dataConvert = [
            "customer_id" =>$customer->id,
            "customer_code" =>$customer->customer_code,
            "closing_date" =>$customer->closing_date,
            "startDateByClosingDate" =>$startDateByClosingDate,
            "endDateByClosingDate" =>$endDateByClosingDate,
            "fistDayMonth" =>$fistDayMonth,
            "lastDayMonth" =>$lastDayMonth,
            "aboutDateJapan" =>$aboutDateJapan,
//            "payment_require" =>$payment_require,
            "invoice_date" =>$invoice_date,
            "closing_dateName" =>$customer->closing_dateName,
            "month_choose" => Carbon::parse($request->month_year)->format("m"),
            "customer_name" =>$customer->customer_name,
            "person_charge" =>$customer->person_charge,
            "address" =>$customer->address,
            "post_code" =>$customer->post_code,
            "phone" =>$customer->phone,
            "note" =>$customer->note,
            "date_ship_fee" =>[],
            "total_ship_fee_by_closing_date" => "",
//            "total_ship_fee_by_month" => "",
        ];

        // Truy vấn toàn bộ DriverCourse theo customer và theo closing date
        $driverCourseMonthClosingDateQueries = DriverCourse::
        select(
            "courses.customer_id as courses_customer_id",
            "courses.*",
            "driver_courses.date",
            "drivers.car",
        )
//            ->addSelect(DB::raw('SUM(courses.ship_fee) as courses_ship_fee'))
            ->where("courses.customer_id",$customer->id)
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->join('drivers', 'drivers.id', '=', 'driver_courses.driver_id')
//            ->groupBy("courses.customer_id","driver_courses.date")
            ->whereBetween('driver_courses.date', [$startDateByClosingDate, $endDateByClosingDate])
            ->get();
        if (count($driverCourseMonthClosingDateQueries) != 0){
            foreach ($driverCourseMonthClosingDateQueries as $driverCourseMonthClosingDateQuery){
                $dataConvert['date_ship_fee'][] = $driverCourseMonthClosingDateQuery;
            }
        }

        // Truy vấn Tổng toàn bộ CashIn từng customer theo closing date
        $driverCourseClosingDateQuery = DriverCourse::
        select(
            "courses.customer_id as courses_customer_id",
            "driver_courses.date",
        )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_ship_fee_by_closing_date'))
            ->where("courses.customer_id",$customer->id)
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->whereBetween('driver_courses.date', [$startDateByClosingDate, $endDateByClosingDate])
            ->groupBy("courses.customer_id")
            ->first();
        if ($driverCourseClosingDateQuery){
            $dataConvert['total_ship_fee_by_closing_date'] = $driverCourseClosingDateQuery->total_ship_fee_by_closing_date;
        }

//        // Truy vấn Tổng toàn bộ CashIn từng customer theo tháng
//        $cashInTotalMonthQuery = DriverCourse::
//        select(
//            "courses.customer_id as courses_customer_id",
//            "driver_courses.date",
//        )
//            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_ship_fee_by_month'))
//            ->where("courses.customer_id",$customer->id)
//            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//            ->join('customers', 'customers.id', '=', 'courses.customer_id')
//            ->whereBetween('driver_courses.date', [$fistDayMonth, $lastDayMonth])
//            ->groupBy("courses.customer_id")
//            ->first();
//
//        if ($cashInTotalMonthQuery){
//            $dataConvert['total_ship_fee_by_month'] = $cashInTotalMonthQuery->total_ship_fee_by_month;
//        }
        return $dataConvert;
    }

    public function exportSalesDetailPDF($request,$id){
        $data = $this->saleDetailByClosingDate($request,$id);

        $fontDirs = public_path('fonts/');
        // specify the font
        $fontData = [
            'inter' => [
                'R' => 'rounded-mgenplus-1c-regular.ttf',
            ],
        ];
        $mpdf = new Mpdf([
            'fontDir' => $fontDirs,
            'fontdata' => $fontData,
            'default_font' => 'MY_FONT_NAME',
            'format' => 'A4-L'
        ]);

        $html = view('exportSaleDetailPDF', ['data' => $data])->render();
        $mpdf->WriteHTML($html);
        return $mpdf->Output("laraveltuts.pdf","D");

//        return view('testPDF', ['data'  => $data]);
    }
}
