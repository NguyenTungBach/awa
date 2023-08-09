<?php

namespace Repository;

use App\Models\CashOutStatistical;
use App\Models\FinalClosingHistories;
use App\Models\CashOut;
use App\Models\Course;
use App\Models\DriverCourse;
use App\Models\Driver;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class CashOutStatisticalRepository extends BaseRepository implements CashOutStatisticalRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param CashOutStatistical $model
     */

    public function model()
    {
        return CashOutStatistical::class;
    }

    public function createCashOutStatisticalByDriverCourse($driverId, $monthLine)
    {
        // get course by driver id
        $courses = DriverCourse::where('driver_id', $driverId)->with('course')->get();
        // get cash out by driver id
        $cashOuts = CashOut::where('driver_id', $driverId)->get();
        // by month line (start date month line and end date month line)
        $startDateClosing = Carbon::create($monthLine)->startOfMonth()->format('Y-m-d');
        $endDateClosing = Carbon::create($monthLine)->endOfMonth()->format('Y-m-d');

        // payable this month by start date and end date month line
        $payableThisMonth = $courses->whereBetween('date', [$startDateClosing, $endDateClosing])->sum('course.associate_company_fee');

        // balance previous month by start and end month line (payable last month - cash out last month)
        $payableLastMonth = $courses->where('date', '<', $startDateClosing)->sum('course.associate_company_fee');
        $totalCashOutLastMonth = $cashOuts->where('payment_date', '<', $startDateClosing)->sum('cash_out');
        $balancePreviousMonth = $payableLastMonth - $totalCashOutLastMonth;

        // total cash out current by start date and end date month line
        $totalCashOutCurrent = $cashOuts->whereBetween('payment_date', [$startDateClosing, $endDateClosing])->sum('cash_out');

        $result = CashOutStatistical::create([
            'driver_id' => $driverId,
            'month_line' => $monthLine,
            'balance_previous_month' => $balancePreviousMonth,
            'payable_this_month' => $payableThisMonth,
            'total_cash_out_current' => $totalCashOutCurrent,
        ]);

        return $result;
    }

    public function updateCashOutStatisticalByDriverCourse($driverId, $monthLine, $courseId)
    {
        // get cash out statistical by driver id and month line
        $cashOutStatisticals = CashOutStatistical::where('driver_id', $driverId)->where('month_line', $monthLine)->first();
        $payableCurrentMonth = $cashOutStatisticals->payable_this_month;
        $id = $cashOutStatisticals->id;
        // get course id
        $course = Course::find($courseId);
        $moneyAssociateAdd = $course->associate_company_fee;

        $input = [];
        $input['driver_id'] = $driverId;
        $input['month_line'] = $monthLine;
        $input['balance_previous_month'] = $cashOutStatisticals->balance_previous_month;
        // current payable + additional associate by driver course
        $input['payable_this_month'] = $payableCurrentMonth + $moneyAssociateAdd;
        $input['total_cash_out_current'] = $cashOutStatisticals->total_cash_out_current;

        $result = CashOutStatisticalRepository::update($input, $id);

        return $result;
    }

    public function updateCashOutStatisticalByCashOut($input)
    {
        $monthLine = date('Y-m', strtotime(Carbon::createFromDate($input['payment_date'])));
        // by month line (start date month line and end date month line)
        $startDateClosing = Carbon::createFromDate($input['payment_date'])->startOfMonth()->format('Y-m-d');
        $endDateClosing = Carbon::createFromDate($input['payment_date'])->endOfMonth()->format('Y-m-d');

        // get cash out statistical by driver id and month line
        $cashOutStatisticals = CashOutStatistical::where('driver_id', $input['driver_id'])->where('month_line', $monthLine)->first();
        $id = $cashOutStatisticals->id;

        // get cashout by driver id and where payment date = by month line (start date month line and end date month line)
        $cashOuts = CashOut::where('driver_id', $input['driver_id'])->whereBetween('payment_date', [$startDateClosing, $endDateClosing])->get();
        $totalCashOutCurrent = $cashOuts->sum('cash_out');

        $data = [];
        $data['driver_id'] = $input['driver_id'];
        $data['month_line'] = $monthLine;
        $data['balance_previous_month'] = $cashOutStatisticals->balance_previous_month;
        $data['payable_this_month'] = $cashOutStatisticals->payable_this_month;
        $data['total_cash_out_current'] = $totalCashOutCurrent;

        $result = CashOutStatisticalRepository::update($data, $id);

        return $result;
    }

    public function getAllCashOutStatisticalByDriver($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'drivers.id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');
        $input['month_line'] = Arr::get($input, 'month_line', Carbon::now()->format('Y-m'));

        $monthLine = $input['month_line'];

        // dieu kien de fillter month
        // 1: cashOutStatistical.month_line thuoc input['month_year']: where('cashOutStatistical.month_line', $monthYear)

        $drivers = Driver::where('type', 4)
                    ->with(['cashOutStatistical' => function ($query) use($monthLine) {
                        $query->where('month_line', $monthLine);
                    }]);

        $drivers = $drivers->get();

        $data = [];
        foreach ($drivers as $key => $value) {
            // drivers
            $data[$key]['id'] = $value->id;
            $data[$key]['type'] = $value->type;
            $data[$key]['driver_code'] = $value->driver_code;
            $data[$key]['driver_name'] = $value->driver_name;
            // driver_courses
            // $data[$key]['cash_out_statistical'] = $value->cashOutStatistical;
            if ($value->cashOutStatistical->isEmpty()) {
                // empty
                $data[$key]['month_line'] = 0;
                // no cuoi thang truoc
                $data[$key]['balance_previous_month'] = 0;
                // no thang nay
                $data[$key]['payable_this_month'] = 0;
                // tong no thang nay
                $data[$key]['total_payable'] = 0;
                // so tien da tra thang nay
                $data[$key]['total_cash_out_current'] = 0;
                // no lai thang nay
                $data[$key]['balance_current'] = 0;
            } else {
                // not empty
                foreach ($value->cashOutStatistical as $k => $item) {
                    $data[$key]['month_line'] = $item['month_line'];
                    // no cuoi thang truoc
                    $data[$key]['balance_previous_month'] = $item['balance_previous_month'];
                    // no thang nay
                    $data[$key]['payable_this_month'] = $item['payable_this_month'];
                    // tong no thang nay
                    $data[$key]['total_payable'] = $item['balance_previous_month'] + $item['payable_this_month'];
                    // so tien da tra thang nay
                    $data[$key]['total_cash_out_current'] = $item['total_cash_out_current'];
                    // no lai thang nay
                    $data[$key]['balance_current'] = $item['balance_previous_month'] + $item['payable_this_month'] - $item['total_cash_out_current'];
                }
            }
        }

        $result = $data;

        return $result;
    }

    public function getDetailCashOutStatistical($input)
    {
        $input['month_line'] = empty($input['month_line']) ? date('Y-m', strtotime(Carbon::now())) : $input['month_line'];
        $result = CashOutStatistical::where('driver_id', $input['driver_id'])
                    ->where('month_line', $input['month_line'])
                    ->with('driver')->first();

        $result['driver_code'] = $result->driver->driver_code;
        $result['driver_name'] = $result->driver->driver_name;
        $result['total_payable'] = $result->balance_previous_month + $result->payable_this_month;
        $result['balance_current'] = $result->balance_previous_month + $result->payable_this_month - $result->total_cash_out_current;
        unset($result['driver']);

        return $result;
    }

    public function updateCashOutStatisticalByCourse($input)
    {
        // course_id find course, ship_date
        $course = Course::find($input['course_id']);
        // course_id, ship_date => driver_course => get driver_id, date
        $driverCourse = DriverCourse::where('course_id', $course->id)->where('date', $course->ship_date)->first();
        $month = date('Y-m', strtotime($driverCourse->date));
        // driver_id, date => cash_out_statistical
        $statistical = CashOutStatistical::where('driver_id', $driverCourse->driver_id)->where('month_line', $month)->first();

        // spec: change associate_company_fee => change payable_this_month of cash_out_statistical
        // payable_this_month =  sum(associate_company_fee) all off this month
        // case 1: update couse when month < month now => update cash_out_statistical to now
        // get all driver_course by driver_id and where between month course change
        $startOfMonth = Carbon::create($statistical->month_line)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($statistical->month_line)->endOfMonth()->format('Y-m-d');
        $driverCourses = DriverCourse::where('driver_id', $statistical->driver_id)->whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        // get all course by driver_course
        $arrCourse = $driverCourses->pluck('course_id')->toArray();
        $arrAssociateFee = [];
        foreach ($arrCourse as $key => $value) {
            // dd('$value', $value);
            $arrAssociateFee[$key] = Course::find($value)->associate_company_fee;
        }

        dd('arrAssociateFee', array_sum($arrAssociateFee));

        // 
        // case 2: update couse when month = month now => update cash_out_statistical now

        dd('input update', $input);

    }
}
