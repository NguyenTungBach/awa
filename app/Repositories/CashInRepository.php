<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace Repository;

use App\Http\Resources\CashInResource;
use App\Models\CashIn;
use App\Models\CashInStatical;
use App\Models\Customer;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Repositories\Contracts\CashInRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CashInRepository extends BaseRepository implements CashInRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param CashIn $model
       */

    public function model()
    {
        return CashIn::class;
    }

    public function create(array $attributes)
    {
        //Lấy thông tin closing_date để lấy khoảng hạn thanh toán
        //Tách ngày thanh toán để lấy tháng năm
        $customer = Customer::find($attributes["customer_id"]);
        $closing_dateStart = $this->getClosing_dateStart($customer->closing_date,$attributes['payment_date']);
        $closing_dateEnd = $this->getClosing_dateEnd($customer->closing_date,$attributes['payment_date']);

        $month_year = Carbon::parse($attributes['payment_date'])->format("Y-m");
        //Kiểm tra ngày payment_date có nằm trong ngày chốt lịch final_closing_histories
        $checkFinalClosingHistories = FinalClosingHistories::where('month_year', $month_year)
            ->exists();
        if ($checkFinalClosingHistories){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.final_closing_histories',[
                "attribute"=> $attributes['payment_date']
            ]));
        }

        //Kiểm tra nếu payment_date của customer_id này đã tồn tại rồi thì không được tạo
        $checkCashInPayment = CashIn::
        where("payment_date",$attributes['payment_date'])
        ->where("customer_id",$attributes['customer_id'])
        ->first();

        if ($checkCashInPayment){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.unique',[
                "attribute"=> $attributes['payment_date']
            ]));
        }

        try {
            DB::beginTransaction();
            // Lưu tiền cash-in
            CashIn::create([
                "customer_id" => $attributes["customer_id"],
                "cash_in" => $attributes["cash_in"],
                "payment_method" => $attributes["payment_method"],
                "payment_date" => $attributes["payment_date"],
                "status" => 1,
            ]);

            // Truy vấn tổng tất cả cash-in của customer đó có trong khoảng closing date để cập nhật tiền cash-in-statics
            $totalCashIn = CashIn::
            select("customer_id")
                ->addSelect(DB::raw('SUM(cash_in) as total_cash_in'))
                ->where("customer_id",$attributes["customer_id"])
                ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
                ->groupBy("customer_id")
                ->first();

            // Số tiền CashInStatical đã được cập nhật hoặc tạo trong driver_course
            // Truy vấn cập nhật tiền CashInStatical theo customer_id và theo month_line
            $closing_dateForCashInStatical = $this->checkClosing_dateForCashInStatical($customer->closing_date,$attributes['payment_date']);
            $cashInStatical = CashInStatical::
            where("month_line",$closing_dateForCashInStatical)
                ->where("customer_id",$attributes["customer_id"])
                ->first();

            // Truy vấn tổng số tiền phải nhận trong tháng này
            $driverCourseTotal = DriverCourse::
            select(
                "customers.id as customers_id",
            )
                ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
                ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                ->join('customers', 'customers.id', '=', 'courses.customer_id')
                ->where("customer_id", $attributes["customer_id"])
                ->whereBetween('driver_courses.date', [$closing_dateStart, $closing_dateEnd])
                ->groupBy("customers.id")
                ->first();
            $getReceivable_this_month = 0;
            if ($driverCourseTotal != null){
                $getReceivable_this_month = $driverCourseTotal->total_course_ship_fee;
            }

            // Nếu CashInStatical của tháng này với closing date chưa có thì tạo
            if ($cashInStatical == null){ // Trường hợp gửi tiền quá hạn sang tháng sau
                // Truy vấn tiền closing_date tháng trước là tháng gần nhất
                $cashInStaticalPrevMonth = CashInStatical::
                where("month_line","<",$closing_dateForCashInStatical)
                    ->where("customer_id",$attributes["customer_id"])
                    ->orderBy("month_line", "desc")
                    ->first();
                $getBalance_previous_month = 0;
                if ($cashInStaticalPrevMonth != null){
                    $getBalance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
                }

                CashInStatical::create([
                    "customer_id"=>$attributes["customer_id"],
                    "month_line"=>$closing_dateForCashInStatical,
                    "balance_previous_month"=>$getBalance_previous_month, // Lấy tiền tháng trước
                    "receivable_this_month"=> $getReceivable_this_month,
                    "total_cash_in_current"=>$getBalance_previous_month + $getReceivable_this_month - $totalCashIn->total_cash_in
                ]);
            } else{
                // Tìm và cập nhật CashInStatical của customer tháng đó
                $cashInStatical->update([
                    "receivable_this_month"=> $getReceivable_this_month, // cập nhật lại số tiền cần nhận
                    "total_cash_in_current" => $cashInStatical->balance_previous_month + $cashInStatical->receivable_this_month - $totalCashIn->total_cash_in,
                ]);
            }
            DB::commit();
            return $this->responseJson(200, new CashInResource($attributes)); // TODO: Change the autogenerated stub
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }

    }

    public function checkClosing_dateForCashInStatical($closing_date,$date){
        switch ($closing_date){
            case 1:
                // 15
                // So sánh date với ngày 16 tháng trước và 15 tháng này
                $thisMonth_year = Carbon::parse($date)->format("Y-m");
                $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
                $checkDate = Carbon::parse($date);
                $date15ThisMonth = Carbon::parse($thisMonth_year."-15");
                $date16PrevMonth = Carbon::parse($prevMonth_year."-16");
                // Nếu qua ngày 15 tháng này
                if ($checkDate->gt($date15ThisMonth)){
                    // Lấy tháng sau
                    return Carbon::parse($date)->addMonth()->format("Y-m");
                }
                // Nếu nằm trong khoảng ngày 16 tháng trước và 15 tháng này
                if ($checkDate->gte($date16PrevMonth) && $checkDate->lte($date15ThisMonth)){
                    // Lấy tháng này
                    return Carbon::parse($date)->format("Y-m");
                }
            case 2:
                // 20
                // So sánh date với ngày 21 tháng trước và 20 tháng này
                $thisMonth_year = Carbon::parse($date)->format("Y-m");
                $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
                $checkDate = Carbon::parse($date);
                $date20ThisMonth = Carbon::parse($thisMonth_year."-20");
                $date21PrevMonth = Carbon::parse($prevMonth_year."-21");
                // Nếu qua ngày 20 tháng này
                if ($checkDate->gt($date20ThisMonth)){
                    // Lấy tháng sau
                    return Carbon::parse($date)->addMonth()->format("Y-m");
                }
                // Nếu nằm trong khoảng ngày 21 tháng trước và 20 tháng này
                if ($checkDate->gte($date21PrevMonth) && $checkDate->lte($date20ThisMonth)){
                    // Lấy tháng này
                    return Carbon::parse($date)->format("Y-m");
                }
            case 3:
                // 25
                // So sánh date với ngày 26 tháng trước và 25 tháng này
                $thisMonth_year = Carbon::parse($date)->format("Y-m");
                $prevMonth_year = Carbon::parse($date)->subMonth()->format("Y-m");
                $checkDate = Carbon::parse($date);
                $date25ThisMonth = Carbon::parse($thisMonth_year."-25");
                $date26PrevMonth = Carbon::parse($prevMonth_year."-26");
                // Nếu qua ngày 25 tháng này
                if ($checkDate->gt($date25ThisMonth)){
                    // Lấy tháng sau
                    return Carbon::parse($date)->addMonth()->format("Y-m");
                }
                // Nếu nằm trong khoảng ngày 26 tháng trước và 25 tháng này
                if ($checkDate->gte($date26PrevMonth) && $checkDate->lte($date25ThisMonth)){
                    // Lấy tháng này
                    return Carbon::parse($date)->format("Y-m");
                }
            case 4:
                // cuối tháng
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
}
