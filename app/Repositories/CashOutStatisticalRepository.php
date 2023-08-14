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

        // find cash_out_statistical by driver_id gan month_line nhat
        $cashOutStatistical = CashOutStatistical::where('driver_id', $driverId)->orderBy('month_line', 'desc')->first();
        // balance previous month by start and end month line (balance last month + payable last month - cash out last month)
        if (!empty($cashOutStatistical)) {
            $balancePreviousMonth = $cashOutStatistical->balance_previous_month + $cashOutStatistical->payable_this_month - $cashOutStatistical->total_cash_out_current;
        } else {
            // total associate_company_fee before month_line
            $associateBefore = $courses->where('date', '<', $startDateClosing)->sum('course.associate_company_fee');
            // total cash_out before month_line
            $cashOutBefore = $cashOuts->where('payment_date', '<', $startDateClosing)->sum('cash_out');
            $balancePreviousMonth = $associateBefore - $cashOutBefore;
        }

        // payable this month by start date and end date month line
        $payableThisMonth = $courses->whereBetween('date', [$startDateClosing, $endDateClosing])->sum('course.associate_company_fee');

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
        $data = [];
        // get cash out statistical by driver id and month line
        $cashOutStatisticals = CashOutStatistical::where('driver_id', $driverId)->where('month_line', $monthLine)->first();

        // total associate this month by month_line truyen vao
        $totalAssociate = $this->getPayableThisMonth($monthLine, $driverId);
        $data['payable_this_month'] = $totalAssociate;

        $update = CashOutStatisticalRepository::update($data, $cashOutStatisticals->id);
        // update statistical to now
        $this->updateStatisticalToNow($update);

        return $update;
    }

    public function updateCashOutStatisticalByCashOut($input)
    {
        $data = [];
        $monthLine = date('Y-m', strtotime(Carbon::createFromDate($input['payment_date'])));
        // get cash out statistical by driver id and month line
        $cashOutStatisticals = CashOutStatistical::where('driver_id', $input['driver_id'])->where('month_line', $monthLine)->first();

        // by month line (start date month line and end date month line)
        $startOfMonth = Carbon::createFromDate($input['payment_date'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromDate($input['payment_date'])->endOfMonth()->format('Y-m-d');

        // total cash out by driver_id, month_line
        $totalCashOut = CashOut::where('driver_id', $input['driver_id'])->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('cash_out');
        $data['total_cash_out_current'] = $totalCashOut;

        $update = CashOutStatisticalRepository::update($data, $cashOutStatisticals->id);

        $this->updateStatisticalToNow($update);

        return $update;
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
        $data = [];
        // input['course_id'] => driver_course => get driver_id, date
        $driverCourse = DriverCourse::where('course_id', $input['course_id'])->first();
        $month = date('Y-m', strtotime($driverCourse->date));


        // // viet xuong ham chung ben duoi 
        // // get all driver course by driver_id in month line with relationship course => sum(associate_company_fee)
        // $startOfMonth = Carbon::create($driverCourse->date)->startOfMonth()->format('Y-m-d');
        // $endOfMonth = Carbon::create($driverCourse->date)->endOfMonth()->format('Y-m-d');

        // $totalAssociate = DriverCourse::join('courses', 'driver_courses.course_id', '=', 'courses.id')
        //                 ->where('driver_id', $driverCourse->driver_id)
        //                 ->whereBetween('date', [$startOfMonth, $endOfMonth])
        //                 ->sum('courses.associate_company_fee');

        // total associate by driver_id and month_line truyen vao
        $totalAssociate = $this->getPayableThisMonth($month, $driverCourse->driver_id);

        // spec: change associate_company_fee => change payable_this_month of cash_out_statistical
        // payable_this_month =  sum(associate_company_fee) all off this month
        // driver_id, date, totalAssociate => cash_out_statistical
        $statistical = CashOutStatistical::where('driver_id', $driverCourse->driver_id)->where('month_line', $month)->first();
        // update statistical
        $data['payable_this_month'] = $totalAssociate;
        $update = CashOutStatisticalRepository::update($data, $statistical->id);
        // update statistical to now
        $this->updateStatisticalToNow($update);

        return true;
    }

    public function updateStatisticalToNow($cashOutStatistical)
    {
        $statistical = CashOutStatistical::where('driver_id', $cashOutStatistical->driver_id)->where('month_line','>', $cashOutStatistical->month_line)->first();
        if(!$statistical) return;
        $data['balance_previous_month'] = $cashOutStatistical->balance_previous_month + $cashOutStatistical->payable_this_month - $cashOutStatistical->total_cash_out_current;
        $update = CashOutStatisticalRepository::update($data, $statistical->id);

        $this->updateStatisticalToNow($update);
    }

    public function getPayableThisMonth($monthLine, $driverId)
    {
        $startOfMonth = Carbon::create($monthLine)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($monthLine)->endOfMonth()->format('Y-m-d');

        $totalAssociate = DriverCourse::join('courses', 'driver_courses.course_id', '=', 'courses.id')
                        ->select('driver_courses.id AS driver_courses_id', 'driver_courses.driver_id', 'driver_courses.course_id', 'driver_courses.date', 'courses.id AS id', 'courses.ship_date', 'courses.associate_company_fee')
                        ->where('driver_id', $driverId)
                        ->whereBetween('date', [$startOfMonth, $endOfMonth])
                        ->sum('courses.associate_company_fee');

        return $totalAssociate;
    }
}
