<?php

namespace Repository;

use App\Models\CashOutStatistical;
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

        $cashOutStatisticals = DB::table('cash_out_statisticals')
            ->rightJoin('drivers', 'cash_out_statisticals.driver_id', '=', 'drivers.id')
            ->where('drivers.type', 4)
            ->get();

        $collection = $cashOutStatisticals->sortBy([
            [$input['order_by'], $input['sort_by']],
        ]);

        $data = [];
        // $collectionWithMonth = $collection->where('month_line', $input['month_line']);
        foreach ($collection as $key => $value) {
            $driverIdValue = empty($value->driver_id) ? 0 : $value->driver_id;
            $monthLineValue = empty($value->month_line) ? 0 : $value->month_line;
            $balancePreviousMonthValue = empty($value->balance_previous_month) ? 0 : $value->balance_previous_month;
            $payableThisMonthValue = empty($value->payable_this_month) ? 0 : $value->payable_this_month;
            $totalCashOutCurrentValue = empty($value->total_cash_out_current) ? 0 : $value->total_cash_out_current;

            $data[$key]['id'] = $value->id;
            $data[$key]['driver_id'] = $driverIdValue;
            $data[$key]['driver_code'] = $value->driver_code;
            $data[$key]['driver_name'] = $value->driver_name;
            $data[$key]['month_line'] = $monthLineValue;
            // no cuoi thang truoc
            $data[$key]['balance_previous_month'] = $balancePreviousMonthValue;
            // no thang nay
            $data[$key]['payable_this_month'] = $payableThisMonthValue;
            // tong no thang nay
            $data[$key]['total_payable'] = $balancePreviousMonthValue + $payableThisMonthValue;
            // so tien da tra thang nay
            $data[$key]['total_cash_out_current'] = $totalCashOutCurrentValue;
            // no lai thang nay
            $data[$key]['balance_current'] = $balancePreviousMonthValue + $payableThisMonthValue - $totalCashOutCurrentValue;
        }

        // $dataNull = [];
        // // $collectionNotMonth = $collection->where('month_line', '<',$input['month_line']);
        // $collectionNotMonth = $collection->whereBetween('month_line', [NULL, '2023-06']);
        // dd('not month', $collectionNotMonth);

        // foreach ($collectionNotMonth as $key => $value) {
        //     dd($value);
        //     $driverIdValue = empty($value->driver_id) ? 0 : $value->driver_id;
        //     $monthLineValue = empty($value->month_line) ? 0 : $value->month_line;
        //     $balancePreviousMonthValue = empty($value->balance_previous_month) ? 0 : $value->balance_previous_month;
        //     $payableThisMonthValue = empty($value->payable_this_month) ? 0 : $value->payable_this_month;
        //     $totalCashOutCurrentValue = empty($value->total_cash_out_current) ? 0 : $value->total_cash_out_current;

        //     $dataNull[$key]['id'] = $value->id;
        //     $dataNull[$key]['driver_id'] = $driverIdValue;
        //     $dataNull[$key]['driver_code'] = $value->driver_code;
        //     $dataNull[$key]['driver_name'] = $value->driver_name;
        //     $dataNull[$key]['month_line'] = $monthLineValue;
        //     // no cuoi thang truoc
        //     $dataNull[$key]['balance_previous_month'] = $balancePreviousMonthValue;
        //     // no thang nay
        //     $dataNull[$key]['payable_this_month'] = $payableThisMonthValue;
        //     // tong no thang nay
        //     $dataNull[$key]['total_payable'] = $balancePreviousMonthValue + $payableThisMonthValue;
        //     // so tien da tra thang nay
        //     $dataNull[$key]['total_cash_out_current'] = $totalCashOutCurrentValue;
        //     // no lai thang nay
        //     $dataNull[$key]['balance_current'] = $balancePreviousMonthValue + $payableThisMonthValue - $totalCashOutCurrentValue;
        // }

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
        unset($result['driver']);

        return $result;
    }
}
