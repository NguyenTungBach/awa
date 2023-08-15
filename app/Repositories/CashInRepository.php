<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace Repository;

use App\Http\Resources\BaseResource;
use App\Http\Resources\CashInResource;
use App\Models\Calendar;
use App\Models\CashIn;
use App\Models\CashInHistory;
use App\Models\CashInStatical;
use App\Models\Customer;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Contracts\CashInRepositoryInterface;
use App\Repositories\Contracts\CashInStaticalRepositoryInterface;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CashInRepository extends BaseRepository implements CashInRepositoryInterface
{

     public function __construct(Application $app, CashInStaticalRepositoryInterface $cashInStaticalRepository, CalendarRepositoryInterface $calendarRepository)
     {
         parent::__construct($app);
         $this->cashInStaticalRepository = $cashInStaticalRepository;
         $this->calendarRepository = $calendarRepository;
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

    public function listCashIn($request){
        $customer = Customer::find($request->customer_id);

        $getClosingDateByMonthStart = $this->getClosingDateByMonthStart($customer->closing_date,$request->month_year);
        $getClosingDateByMonthEnd = $this->getClosingDateByMonthEnd($customer->closing_date,$request->month_year);

        // Lấy ra danh sách Cash In của customer trong tháng
        $listCashIn = $this->model->query()
            ->where("customer_id",$request->customer_id)
            ->whereBetween('payment_date', [$getClosingDateByMonthStart, $getClosingDateByMonthEnd])
            ->orderBy("payment_date","asc")
            ->whereNull('deleted_at')
            ->get()
            ->filter(function ($data) {
                switch ($data->customer->closing_date){
                    case 1:
                        $data->customer->closing_dateName = trans("customers.closing_date_lang.1");
                        break;
                    case 2:
                        $data->customer->closing_dateName = trans("customers.closing_date_lang.2");
                        break;
                    case 3:
                        $data->customer->closing_dateName = trans("customers.closing_date_lang.3");
                        break;
                    case 4:
                        $data->customer->closing_dateName = trans("customers.closing_date_lang.4");
                        break;
                }
                return $data;
            });

        //Truy vấn lại lấy ra tổng tiền Cash In của customer trong tháng
        $totalCashIn = $this->model->query()
            ->select("customer_id")
            ->addSelect(DB::raw('SUM(cash_in) as total_cash_in'))
            ->where("customer_id",$request->customer_id)
            ->whereBetween('payment_date', [$getClosingDateByMonthStart, $getClosingDateByMonthEnd])
            ->groupBy("customer_id")->first();
        $data = [
            'list_cash_in'=>$listCashIn,
            'total_cash_in'=>$totalCashIn ?? 0,
        ];
        return $data;
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
//            $this->checkUpdateCashInStatical($attributes["customer_id"],$customer->closing_date,$attributes["payment_date"],$closing_dateStart,$closing_dateEnd);
            $this->cashInStaticalRepository->saveCashInStatic($attributes["customer_id"],$attributes['payment_date']);
            DB::commit();
            return $this->responseJson(200, new CashInResource($attributes)); // TODO: Change the autogenerated stub
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }

    public function update(array $attributes, $id)
    {
        $cashIn = CashIn::find($id);
        if ($cashIn == null){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.attribute_not_found',[
                "attribute"=> "cash in",
                "id"=> $id
            ]));
        }

        $month_year = Carbon::parse($attributes['payment_date'])->format("Y-m");
        //Kiểm tra ngày payment_date có nằm trong ngày chốt lịch final_closing_histories
        $checkFinalClosingHistories = FinalClosingHistories::where('month_year', $month_year)
            ->exists();
        if ($checkFinalClosingHistories){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.final_closing_histories',[
                "attribute"=> $attributes['payment_date']
            ]));
        }

        // Nếu payment_date khác ngày thì kiểm tra xem có bị trùng ngày không
        if ($cashIn->payment_date != $attributes['payment_date']){
            // Tìm xem có ngày cashIn này chưa
            $checkPayment_dateCashInExist =  CashIn::
            where("customer_id",$attributes['customer_id'])
            ->where("payment_date",$attributes['payment_date'])->first();

            if ($checkPayment_dateCashInExist){
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.unique',[
                    "attribute"=> $attributes['payment_date']
                ]));
            }
        }

        // Lấy customer_id và payment_date cash in trước khi cập nhật để cập nhật lại cash_in_statisticals
        $customer_idBeforeUpdate = $cashIn->customer_id;
        $customerBeforeUpdate = Customer::find($customer_idBeforeUpdate);
        $payment_dateBeforeUpdate = $cashIn->payment_date;

        try {
            DB::beginTransaction();
            // Cập nhật cash-in
            $cashInUpdate = parent::update($attributes, $id);

            // Lưu lịch sử update
            CashInHistory::create([
                "customer_id" => $cashInUpdate->customer_id,
                "cash_in_id" => $id,
                "type" => 1,
                "cash_in" => $cashInUpdate->cash_in,
                "payment_method" => $cashInUpdate->payment_method,
                "payment_date" => $cashInUpdate->payment_date,
                "note" => $cashInUpdate->note,
                "status" => $cashInUpdate->status,
            ]);

            //Cập nhật lại tìm cash_in_statisticals lúc trước khi update
            // Lấy ra khoảng thời gian closing_date trước khi update
            $getClosingDateBeforeUpdateStart = $this->getClosing_dateStart($customerBeforeUpdate->closing_date,$payment_dateBeforeUpdate);
            $getClosingDateBeforeUpdateEnd = $this->getClosing_dateEnd($customerBeforeUpdate->closing_date,$payment_dateBeforeUpdate);
//            $this->checkUpdateCashInStatical($customer_idBeforeUpdate,$customerBeforeUpdate->closing_date,$payment_dateBeforeUpdate,$getClosingDateBeforeUpdateStart,$getClosingDateBeforeUpdateEnd);
            $this->cashInStaticalRepository->saveCashInStatic($customer_idBeforeUpdate,$payment_dateBeforeUpdate);

            //Cập nhật lại tìm cash_in_statisticals sau khi update
            // Lấy ra khoảng thời gian closing_date sau khi update
            $customerAfterUpdate = Customer::find($cashInUpdate->customer_id);
            $getClosingDateAfterUpdateStart = $this->getClosing_dateStart($customerAfterUpdate->closing_date,$cashInUpdate->payment_date);
            $getClosingDateAfterUpdateEnd = $this->getClosing_dateEnd($customerAfterUpdate->closing_date,$cashInUpdate->payment_date);
//            $this->checkUpdateCashInStatical($cashInUpdate->customer_id,$customerAfterUpdate->closing_date,$cashInUpdate->payment_date,$getClosingDateAfterUpdateStart,$getClosingDateAfterUpdateEnd);
            $this->cashInStaticalRepository->saveCashInStatic($cashInUpdate->customer_id,$cashInUpdate->payment_date);
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }

        return $this->responseJson(200, new BaseResource($attributes)); // TODO: Change the autogenerated stub
    }

    public function delete($id)
    {
        $cashIn =  CashIn::find($id);
        if ($cashIn == null){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.attribute_not_found',[
                "attribute"=> "cash in",
                "id"=> $id
            ]));
        }

        $month_year = Carbon::parse($cashIn->payment_date)->format("Y-m");
        //Kiểm tra ngày payment_date có nằm trong ngày chốt lịch final_closing_histories
        $checkFinalClosingHistories = FinalClosingHistories::where('month_year', $month_year)
            ->exists();
        if ($checkFinalClosingHistories){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.final_closing_histories',[
                "attribute"=> $cashIn->payment_date
            ]));
        }

        // Lấy customer_id và payment_date cash in trước khi cập nhật để cập nhật lại cash_in_statisticals
        $customer_idBeforeDelete = $cashIn->customer_id;
        $customerBeforeDelete = Customer::find($customer_idBeforeDelete);
        $cash_inBeforeDelete = $cashIn->cash_in;
        $payment_dateBeforeDelete = $cashIn->payment_date;
        $payment_methodBeforeDelete = $cashIn->payment_method;
        $noteBeforeDelete = $cashIn->note;
        $statusBeforeDelete = $cashIn->status;

        try {
            DB::beginTransaction();
            // Cập nhật cash-in
            parent::delete($id); // TODO: Change the autogenerated stub;

            // Lưu lịch sử update
            CashInHistory::create([
                "customer_id" => $customer_idBeforeDelete,
                "cash_in_id" => $id,
                "type" => 2,
                "cash_in" => $cash_inBeforeDelete,
                "payment_method" => $payment_methodBeforeDelete,
                "payment_date" => $payment_dateBeforeDelete,
                "note" => $noteBeforeDelete,
                "status" => $statusBeforeDelete,
            ]);

            //Cập nhật lại tìm cash_in_statisticals lúc trước và sau khi delete
            // Lấy ra khoảng thời gian closing_date trước khi delete
            $getClosingDateBeforeDeleteStart = $this->getClosing_dateStart($customerBeforeDelete->closing_date,$payment_dateBeforeDelete);
            $getClosingDateBeforeDeleteEnd = $this->getClosing_dateEnd($customerBeforeDelete->closing_date,$payment_dateBeforeDelete);
            // Cập nhật lại sau khi xóa bằng cách lấy khoảng thời gian trước
//            $this->checkUpdateCashInStatical($customer_idBeforeDelete,$customerBeforeDelete->closing_date,$payment_dateBeforeDelete,$getClosingDateBeforeDeleteStart,$getClosingDateBeforeDeleteEnd);
            $this->cashInStaticalRepository->saveCashInStatic($cashIn->customer_id,$cashIn->payment_date);

            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }

//    public function checkUpdateCashInStatical($customer_id,$closing_date,$payment_date,$closing_dateStart,$closing_dateEnd){
//        // Truy vấn tổng tất cả cash-in của customer đó có trong khoảng closing date để cập nhật tiền cash-in-statics
//        $totalCashIn = CashIn::
//        select("customer_id")
//            ->addSelect(DB::raw('SUM(cash_in) as total_cash_in'))
//            ->where("customer_id",$customer_id)
//            ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
//            ->groupBy("customer_id")
//            ->first();
//
//        // Số tiền CashInStatical đã được cập nhật hoặc tạo trong driver_course
//        // Truy vấn cập nhật tiền CashInStatical theo customer_id và theo month_line
//        $closing_dateForCashInStatical = $this->checkClosing_dateForCashInStatical($closing_date,$payment_date);
//        $cashInStatical = CashInStatical::
//        where("month_line",$closing_dateForCashInStatical)
//            ->where("customer_id",$customer_id)
//            ->first();
//
//        // Truy vấn tổng số tiền phải nhận trong tháng này
//        $driverCourseTotal = DriverCourse::
//        select(
//            "customers.id as customers_id",
//        )
//            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
//            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//            ->join('customers', 'customers.id', '=', 'courses.customer_id')
//            ->where("customer_id", $customer_id)
//            ->whereBetween('driver_courses.date', [$closing_dateStart, $closing_dateEnd])
//            ->groupBy("customers.id")
//            ->first();
//        $getReceivable_this_month = 0;
//        if ($driverCourseTotal != null){
//            $getReceivable_this_month = $driverCourseTotal->total_course_ship_fee;
//        }
//
//        // Truy vấn tiền closing_date tháng trước là tháng gần nhất
//        $cashInStaticalPrevMonth = CashInStatical::
//        where("month_line","<",$closing_dateForCashInStatical)
//            ->where("customer_id",$customer_id)
//            ->orderBy("month_line", "desc")
//            ->first();
//        $balance_previous_month = 0;
//        if ($cashInStaticalPrevMonth != null){
//            $balance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
//        }
//
//        // Nếu CashInStatical của tháng này với closing date chưa có thì tạo
//        if ($cashInStatical == null){ // Trường hợp gửi tiền quá hạn sang tháng sau
//            CashInStatical::create([
//                "customer_id"=>$customer_id,
//                "month_line"=>$closing_dateForCashInStatical,
//                "balance_previous_month"=>$balance_previous_month, // Lấy tiền tháng trước
//                "receivable_this_month"=> $getReceivable_this_month,
//                "total_cash_in_current"=>$balance_previous_month + $getReceivable_this_month - $totalCashIn->total_cash_in
//            ]);
//        } else{
//            // Tìm và cập nhật CashInStatical của customer tháng đó
//            $cashInStatical->update([
//                "receivable_this_month"=> $getReceivable_this_month, // cập nhật lại số tiền cần nhận
//                "total_cash_in_current" => $cashInStatical->balance_previous_month + $cashInStatical->receivable_this_month - $totalCashIn->total_cash_in,
//            ]);
//            $checkMonthForThisDate = $this->checkClosing_dateForCashInStatical($closing_date,$payment_date);
//            // Kiểm tra xem có tháng các tháng còn lại không để cập nhật
//            $checkCashInStaticalUpdates = CashInStatical::
//            where("customer_id",$customer_id)
//                ->where("month_line",">",$checkMonthForThisDate)
//                ->orderBy("month_line", "asc")
//                ->get();
//            // Nếu có thì cập nhật các bản ghi còn lại
//            $update_balance_previous_month = $balance_previous_month;
//            if (count($checkCashInStaticalUpdates) != 0) {
//                foreach ($checkCashInStaticalUpdates as $cashInStaticalUpdate) {
//                    // 1. Truy vấn tổng số tiền trong theo từng tháng theo closing date
//                    // 1.1 Lấy ra khoảng thời gian theo closing_date
//                    $updateStartDateByClosingDate = "";
//                    $updateEndDateByClosingDate = "";
//                    switch ($closing_date) {
//                        case 1:
//                            $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m") . "16";
//                            $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m") . "15";
//                            break;
//                        case 2:
//                            $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m") . "21";
//                            $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m") . "20";
//                            break;
//                        case 3:
//                            $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m") . "26";
//                            $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m") . "25";
//                            break;
//                        case 4:
//                            $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->startOfMonth();
//                            $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->endOfMonth();
//                            break;
//                    }
//                    // 1.2 truy vấn tổng số tiền cần nhận trong tháng cần update lại theo closing date
//                    $updateByDriverCourseTotal = DriverCourse::
//                    select(
//                        "customers.id as customers_id",
//                    )
//                        ->addSelect(\DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
//                        ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
//                        ->join('customers', 'customers.id', '=', 'courses.customer_id')
//                        ->where("customer_id", $customer_id)
//                        ->whereBetween('driver_courses.date', [$updateStartDateByClosingDate, $updateEndDateByClosingDate])
//                        ->groupBy("customers.id")
//                        ->first();
//
//                    // 1.3 truy vấn tổng số tiền phải nhận trong tháng cần update lại theo closing date
//                    $updateByTotalCashIn = 0;
//                    $totalCashInQuery = CashIn::
//                    select("customer_id")
//                        ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
//                        ->where("customer_id", $customer_id)
//                        ->whereBetween('payment_date', [$updateStartDateByClosingDate, $updateEndDateByClosingDate])
//                        ->groupBy("customer_id")
//                        ->first();
//                    if ($totalCashInQuery != null) {
//                        $updateByTotalCashIn = $totalCashInQuery->total_cash_in;
//                    }
//
//                    // 1.4 Update lại tiền
//                    $cashInStaticalUpdate->update([
//                        'balance_previous_month' => $update_balance_previous_month, // tiền tháng trước
//                        "receivable_this_month" => $updateByDriverCourseTotal, // tiền phải nhận tháng này
//                        'total_cash_in_current' => $update_balance_previous_month + $updateByDriverCourseTotal - $updateByTotalCashIn,
//                    ]);
//                    $update_balance_previous_month = $update_balance_previous_month + $updateByDriverCourseTotal - $updateByTotalCashIn;
//                }
//            }
//        }
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

    public function getClosingDateByMonthStart($closing_date,$month_year){
        switch ($closing_date){
            case 1:
                return Carbon::createFromFormat('Y-m', $month_year)->subMonth()->format("Y-m")."-16";
            case 2:
                return Carbon::createFromFormat('Y-m', $month_year)->subMonth()->format("Y-m")."-21";
            case 3:
                return Carbon::createFromFormat('Y-m', $month_year)->subMonth()->format("Y-m")."-26";
            case 4:
                return Carbon::createFromFormat('Y-m', $month_year)->startOfMonth()->format("Y-m-d");
        }
    }

    public function getClosingDateByMonthEnd($closing_date,$month_year){
        switch ($closing_date){
            case 1:
                return Carbon::createFromFormat('Y-m', $month_year)->format("Y-m")."-15";
            case 2:
                return Carbon::createFromFormat('Y-m', $month_year)->format("Y-m")."-20";
            case 3:
                return Carbon::createFromFormat('Y-m', $month_year)->format("Y-m")."-25";
            case 4:
                return Carbon::createFromFormat('Y-m', $month_year)->endOfMonth()->format("Y-m-d");
        }
    }

}
