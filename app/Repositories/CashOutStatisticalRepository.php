<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\CashOutStatistical;
use App\Models\CashOut;
use App\Models\Course;
use App\Models\DriverCourse;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // closing date by month line
        $startDateClosing = date('Y-m-26', strtotime('-1 month'));
        $endDateClosing = date('Y-m-25', strtotime(now()));

        // payable this month by closing date - 25
        $payableThisMonth = $courses->whereBetween('date', [$startDateClosing, $endDateClosing])->sum('course.associate_company_fee');

        // balance previous month by closing date - 25 (payable last month - cash out last month)
        $payableLastMonth = $courses->where('date', '<', $startDateClosing)->sum('course.associate_company_fee');
        $totalCashOutLastMonth = $cashOuts->where('payment_date', '<', $startDateClosing)->sum('cash_out');
        $balancePreviousMonth = $payableLastMonth - $totalCashOutLastMonth;

        // total cash out current by closing date - 25
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
        $defaultDate = Carbon::createFromDate($input['payment_date']);
        $monthLine = date('Y-m', strtotime($input['payment_date']));
        // closing date
        $startDateClosing = $defaultDate->subMonth()->format('Y-m-26');
        $endDateClosing = date('Y-m-25', strtotime($monthLine));

        // get cash out statistical by driver id and month line
        $cashOutStatisticals = CashOutStatistical::where('driver_id', $input['driver_id'])->where('month_line', $monthLine)->first();
        $id = $cashOutStatisticals->id;

        // get cashout by driver id and where payment date = closing date - 25
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
}
