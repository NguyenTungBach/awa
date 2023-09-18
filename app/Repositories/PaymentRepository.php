<?php

namespace Repository;

use App\Models\Driver;
use App\Models\Calendar;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param Driver $model
     */

    public function model()
    {
        return Driver::class;
    }

    public function getListAll($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'drivers.id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');
        $input['month_year'] = Arr::get($input, 'month_year', Carbon::now()->format('Y-m'));
        
        $startOfMonth = Carbon::createFromDate($input['month_year'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromDate($input['month_year'])->endOfMonth()->format('Y-m-d');
        $monthYear = $input['month_year'];

        $calendars = Calendar::whereBetween('date', [$startOfMonth, $endOfMonth])->get()->pluck('date')->toArray();

        // 2 dieu kien de fillter month
        // 1: driverCourses.date thuoc input['month_year']: whereBetween('driverCourses.date', [$startOfMonth, $endOfMonth])
        // 2: course.ship_date thuoc input['month_year']: whereBetween('course.ship_date', [$startOfMonth, $endOfMonth])

        $check = $this->checkFinalClosing($monthYear);
        if ($check) {
            $drivers = Driver::where('type', 4)
                        ->with(['driverCourses' => function ($query) use($startOfMonth, $endOfMonth) {
                            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
                            $query->join('courses', 'driver_courses.course_id', '=', 'courses.id');
                            $query->whereBetween('courses.ship_date', [$startOfMonth, $endOfMonth]);
                        }])
                        // ->with(['cashOutStatistical' => function ($query) use($monthYear) {
                        //     $query->where('month_line', $monthYear);
                        // }]);
                        ->whereRaw('IF(start_date IS NOT NULL, DATE_FORMAT(start_date, "%Y-%m") <= ?, DATE_FORMAT(created_at, "%Y-%m") <= ?)', [$monthYear, $monthYear])
                        ->where(function ($query) use ($monthYear) {
                            $query->whereNull('end_date')
                                ->orWhereRaw('DATE_FORMAT(end_date, "%Y-%m") >= ?', [$monthYear]);
                        })
                        ->orderBy($input['order_by'], $input['sort_by']);
        } else {
            $driverIdByFinal = FinalClosingHistories::where('month_year', $monthYear)->first()->pluck('driver_ids');
            $driverIdByFinal = json_decode($driverIdByFinal[0]);
            $drivers = Driver::whereIn('id', $driverIdByFinal)->where('type', 4)
                        ->with(['driverCourses' => function ($query) use($startOfMonth, $endOfMonth) {
                            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
                            $query->join('courses', 'driver_courses.course_id', '=', 'courses.id');
                            $query->whereBetween('courses.ship_date', [$startOfMonth, $endOfMonth]);
                        }])
                        ->orderBy($input['order_by'], $input['sort_by']);
        }

        $drivers = $drivers->get();

        $data = [];
        $totalPayable = 0;
        $totalPayableDay = [];
        foreach ($drivers as $key => $value) {
            // drivers
            $data[$key]['id'] = $value->id;
            $data[$key]['type'] = $value->type;
            $data[$key]['driver_code'] = $value->driver_code;
            $data[$key]['driver_name'] = $value->driver_name;
            $data[$key]['closing_date'] = '25日';
            $data[$key]['vehicle_number'] = empty($value->car) ? '' : $value->car;
            // driver_courses
            // $data[$key]['driver_course'] = $value->driverCourses;

            $sum = 0;
            $totals = [];
            if ($value->driverCourses->isEmpty()) {
                // empty
                $data[$key]['courses']['course_id'] = 0;
                $data[$key]['courses']['course_name'] = 0;
                $data[$key]['courses']['date'] = 0;
                $data[$key]['courses']['ship_date'] = 0;
                $data[$key]['courses']['associate_company_fee'] = 0;
            } else {
                // not empty
                foreach ($value->driverCourses as $k => $item) {
                    if (isset($item['associate_company_fee'])) {
                        $sum += $item['associate_company_fee'];
                    }
                    $shipDate = $item['ship_date'];
                    $associateCompanyFee = floatval($item['associate_company_fee']);
                    // neu ton tai ship date => += associate_company_fee, neu khong ton tai ship date = associate_company_fee
                    if (isset($totals[$shipDate])) {
                        $totals[$shipDate] += $associateCompanyFee;
                    } else {
                        $totals[$shipDate] = $associateCompanyFee;
                    }
                    $data[$key]['courses'][$k]['course_id'] = $item->course_id;
                    $data[$key]['courses'][$k]['course_name'] = $item->course_name;
                    $data[$key]['courses'][$k]['date'] = $item->date;
                    $data[$key]['courses'][$k]['ship_date'] = $item->ship_date;
                    $data[$key]['courses'][$k]['associate_company_fee'] = $item->associate_company_fee;
                }
            }

            // tinh tong tien ngay theo tung driver
            $data[$key]['total_payable_day'] = $totals;
            // tinh tong tien tat ca theo ngay
            foreach ($data[$key]['total_payable_day'] as $date => $amount) {
                if (isset($totalPayableDay[$date])) {
                    $totalPayableDay[$date] += $amount;
                } else {
                    $totalPayableDay[$date] = $amount;
                }
            }

            // tinh tong tien thang theo tung driver
            $data[$key]['payable_this_month'] = $sum;
            // tinh tong tien tat ca theo thang
            $payableThisMonth = floatval($data[$key]['payable_this_month']);
            $totalPayable += $payableThisMonth;
        }

        $listDataResult = [];
        foreach ($data as $kItem => $item) {
            $listDataResult[$kItem]['id'] = $item['id'];
            $listDataResult[$kItem]['driver_code'] = $item['driver_code'];
            $listDataResult[$kItem]['driver_name'] = $item['driver_name'];
            $listDataResult[$kItem]['closing_date'] = $item['closing_date'];
            $listDataResult[$kItem]['vehicle_number'] = $item['vehicle_number'];
            foreach ($calendars as $kCalendar => $calendar) {
                if (!empty($item['total_payable_day']) && array_key_exists($calendar, $item['total_payable_day'])) {
                    $listDataResult[$kItem]['total_payable_day'][$kCalendar]['date'] = $calendar;
                    $listDataResult[$kItem]['total_payable_day'][$kCalendar]['payment'] = $item['total_payable_day'][$calendar];
                } else {
                    $listDataResult[$kItem]['total_payable_day'][$kCalendar]['date'] = $calendar;
                    $listDataResult[$kItem]['total_payable_day'][$kCalendar]['payment'] = 0;
                }
            }
            $listDataResult[$kItem]['payable_this_month'] = $item['payable_this_month'];
        }

        $totalPayableDayResult = [];
        foreach ($calendars as $kCalendar => $calendar) {
            if (array_key_exists($calendar, $totalPayableDay)) {
                $totalPayableDayResult[$kCalendar]['date'] = $calendar;
                $totalPayableDayResult[$kCalendar]['pay'] = $totalPayableDay[$calendar];
            } else {
                // $totalPayableDayResult[$calendar] = 0;
                $totalPayableDayResult[$kCalendar]['date'] = $calendar;
                $totalPayableDayResult[$kCalendar]['pay'] = 0;
            }
        }

        $result = [
            'list_data' => $listDataResult,
            'sum_total_day' => $totalPayableDayResult,
            'sum_total_month' => $totalPayable
        ];

        return $result;
    }

    public function getAll($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'drivers.id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');
        $input['month_year'] = Arr::get($input, 'month_year', Carbon::now()->format('Y-m'));
        
        $startOfMonth = Carbon::createFromDate($input['month_year'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromDate($input['month_year'])->endOfMonth()->format('Y-m-d');
        $monthYear = $input['month_year'];

        // 2 dieu kien de fillter month
        // 1: driverCourses.date thuoc input['month_year']: whereBetween('driverCourses.date', [$startOfMonth, $endOfMonth])
        // 2: course.ship_date thuoc input['month_year']: whereBetween('course.ship_date', [$startOfMonth, $endOfMonth])

        $drivers = Driver::where('type', 4)
                    ->with(['driverCourses' => function ($query) use($startOfMonth, $endOfMonth) {
                        $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
                        $query->join('courses', 'driver_courses.course_id', '=', 'courses.id');
                        $query->whereBetween('courses.ship_date', [$startOfMonth, $endOfMonth]);
                        // dd($query->toSql());
                    }])
                    ->orderBy($input['order_by'], $input['sort_by']);
                    // ->with(['cashOutStatistical' => function ($query) use($monthYear) {
                    //     $query->where('month_line', $monthYear);
                    // }]);

        $drivers = $drivers->get();

        $data = [];
        $totalPayable = 0;
        $totalPayableDay = [];
        foreach ($drivers as $key => $value) {
            // drivers
            $data[$key]['id'] = $value->id;
            $data[$key]['type'] = $value->type;
            $data[$key]['driver_code'] = $value->driver_code;
            $data[$key]['driver_name'] = $value->driver_name;
            $data[$key]['closing_date'] = '25日';
            $data[$key]['vehicle_number'] = empty($value->car) ? '' : $value->car;
            // driver_courses
            // $data[$key]['driver_course'] = $value->driverCourses;

            $sum = 0;
            $totals = [];
            if ($value->driverCourses->isEmpty()) {
                // empty
                $data[$key]['courses']['course_id'] = 0;
                $data[$key]['courses']['course_name'] = 0;
                $data[$key]['courses']['date'] = 0;
                $data[$key]['courses']['ship_date'] = 0;
                $data[$key]['courses']['associate_company_fee'] = 0;
            } else {
                // not empty
                foreach ($value->driverCourses as $k => $item) {
                    if (isset($item['associate_company_fee'])) {
                        $sum += $item['associate_company_fee'];
                    }
                    $shipDate = $item['ship_date'];
                    $associateCompanyFee = floatval($item['associate_company_fee']);
                    // neu ton tai ship date => += associate_company_fee, neu khong ton tai ship date = associate_company_fee
                    if (isset($totals[$shipDate])) {
                        $totals[$shipDate] += $associateCompanyFee;
                    } else {
                        $totals[$shipDate] = $associateCompanyFee;
                    }
                    $data[$key]['courses'][$k]['course_id'] = $item->course_id;
                    $data[$key]['courses'][$k]['course_name'] = $item->course_name;
                    $data[$key]['courses'][$k]['date'] = $item->date;
                    $data[$key]['courses'][$k]['ship_date'] = $item->ship_date;
                    $data[$key]['courses'][$k]['associate_company_fee'] = $item->associate_company_fee;
                }
            }

            // tinh tong tien ngay theo tung driver
            $data[$key]['total_payable_day'] = $totals;
            // tinh tong tien tat ca theo ngay
            foreach ($data[$key]['total_payable_day'] as $date => $amount) {
                if (isset($totalPayableDay[$date])) {
                    $totalPayableDay[$date] += $amount;
                } else {
                    $totalPayableDay[$date] = $amount;
                }
            }

            // tinh tong tien thang theo tung driver
            $data[$key]['payable_this_month'] = $sum;
            // tinh tong tien tat ca theo thang
            $payableThisMonth = floatval($data[$key]['payable_this_month']);
            $totalPayable += $payableThisMonth;
        }

        $result = [
            'list_data' => $data,
            'sum_total_day' => $totalPayableDay,
            'sum_total_month' => $totalPayable
        ];

        return $result;
    }

    public function checkFinalClosing($monthYear) {
        $result = [];
        $result = FinalClosingHistories::where('month_year', '=', $monthYear)->first();
        if (empty($result)) {
            return true;
        }

        return false;
    }
}
