<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace Repository;

use App\Http\Resources\BaseResource;
use App\Models\Calendar;
use App\Models\CashIn;
use App\Models\CashInStatical;
use App\Models\Course;
use App\Models\Customer;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\TemporaryClosingHistories;
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

class CashInStaticalRepository extends BaseRepository implements CashInStaticalRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param CashInStatical $model
       */

    public function model()
    {
        return CashInStatical::class;
    }

    public function getListCashInStatical($request){
        // Tim đến tất cả customer và xem đã có bản ghi tháng này theo closing date chưa
        $customers = Customer::all();
        foreach ($customers as $customer){
            $cashInStatic = CashInStatical::
            where("customer_id",$customer->id)
            ->where("month_line",$request->month_year)->first();
            // Trường hợp nếu chưa có thì tạo CashInStatical
//            if ($cashInStatic == null){
//                try {
//                    DB::beginTransaction();
//                    $this->saveCashInStaticMonth($customer->id,$request->month_year);
//                    DB::commit();
//                } catch (\Exception $exception){
//                    DB::rollBack();
//                    return $exception;
//                }
//            }
            try {
                DB::beginTransaction();
                $this->saveCashInStaticMonth($customer->id,$request->month_year);
                DB::commit();
            } catch (\Exception $exception){
                DB::rollBack();
                return $exception;
            }
        }

        // Kiểm tra xem có final tháng trước để cho xem tháng này
        $monthPrev = Carbon::parse($request->month_year)->subMonth()->format('Y-m'); // Lấy tháng trước
        $checkFinalMonthPrev = FinalClosingHistories::where("month_year",$monthPrev)->first();
        $checkFinal = false;
        // Kiểm tra xem tháng trước có không
        if ($checkFinalMonthPrev != null){
            $checkFinal = true;
        }

        // Sau khi kiểm tra đủ cashInStatic mới truy vấn
        // Truy vấn tất cả cashInStatic theo month_year
        // Trường hợp truy vấn tháng trước đã có finnal thì lấy theo balance_previous_month không thì theo balance_temp
        if ($checkFinal){
            $cashInStatics = CashInStatical::query()
                ->select(
                    'customer_id',
                    'month_line',
                    'balance_previous_month',
                    'receivable_this_month',
                    'total_cash_in_current',
                    'balance_temp',
                )
                ->addSelect(DB::raw('(balance_previous_month+receivable_this_month) as total_account_receivable'))
                ->where("month_line",$request->month_year)
                ->get();
        } else{
            $cashInStatics = CashInStatical::query()
                ->select(
                    'customer_id',
                    'month_line',
                    'balance_previous_month',
                    'receivable_this_month',
                    'total_cash_in_current',
                    'balance_temp',
                )
                ->addSelect(DB::raw('(balance_temp+receivable_this_month) as total_account_receivable'))
                ->where("month_line",$request->month_year)
                ->get();
        }

        // Truy vấn tất cả cash in statics theo customer
        $cashInsByCustomers = [];
        foreach ($customers as $customer){
//            $closing_dateStart = $this->getClosingDateByMonthStart($customer->closing_date,$request->month_year);
//            $closing_dateEnd = $this->getClosingDateByMonthEnd($customer->closing_date,$request->month_year);

            $closing_dateStart = Carbon::parse($request->month_year)->firstOfMonth()->format('Y-m-d');
            $closing_dateEnd = Carbon::parse($request->month_year)->lastOfMonth()->format('Y-m-d');

            $cashIn = CashIn::select(
                'cash_ins.customer_id',
            )
            ->addSelect(DB::raw('COALESCE(SUM(cash_ins.cash_in),0) as total_cash_in'))
            ->where("customer_id",$customer->id)
            ->whereBetween('payment_date', [$closing_dateStart, $closing_dateEnd])->first();

            $cashInsByCustomers[] = [
                "customer_id"=>$customer->id,
                "customer_code"=>$customer->customer_code,
                "customer_name"=>$customer->customer_name,
                "total_cash_in"=>$cashIn->total_cash_in,
            ];
        }

        // Nhóm tất cả dữ liệu theo mỗi tổng tiền cashIn theo customer
        $dataResults = [];

        foreach ($cashInsByCustomers as $cashInsByCustomer){
            // nhóm dữ liệu từ danh sách cashInStatic theo month_year
            foreach ($cashInStatics as $cashInStatic){
                if ($cashInsByCustomer['customer_id'] ==$cashInStatic->customer_id){
                    $dataConverts = [
                        'customer_id' => $cashInsByCustomer['customer_id'] ,
                        'customer_code' => $cashInsByCustomer['customer_code'] ,
                        'customer_name' => $cashInsByCustomer['customer_name'] ,
                        'month_line' => $cashInStatic->month_line,
                        'balance_previous_month' => $checkFinal ? $cashInStatic->balance_previous_month : $cashInStatic->balance_temp, // Tiền nợ tháng trước
                        'receivable_this_month' => $cashInStatic->receivable_this_month, // Tiền sẽ nhận tháng này
                        'total_account_receivable' => $cashInStatic->total_account_receivable, // Tổng tiền phải nhận tháng này
                        'total_cash_in_of_current_month' => $cashInsByCustomer['total_cash_in'], // Tiền trả tháng này
                        'total_cash_in_current' => $checkFinal ? $cashInStatic->total_cash_in_current : ($cashInStatic->balance_temp + $cashInStatic->receivable_this_month - $cashInsByCustomer['total_cash_in']), // Tiền nợ còn lại
                    ];
                    $dataResults[] = $dataConverts;
                }
            }
        }

        $collection = collect($dataResults);

        if ($request->field && $request->sortby){
            if ($request->sortby == "asc"){
                $collection = $collection->sortBy($request->field);
            }
            if ($request->sortby == "desc"){
                $collection = $collection->sortByDesc($request->field);
            }
        }

        return $collection;
    }

    public function exportCashInStatical($request){
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        ini_set('max_execution_time', '0');
        $dataForListCashInStatical = $this->getListCashInStatical($request);
        $getMonth_year = explode("-",$request->month_year);
        $start_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::createFromDate(null, $getMonth_year[1], 1)->endOfMonth()->format('Y-m-d');

        $inputFileType = 'Xlsx';
        $inputFileName = base_path('resources/excels/ExportTotalCashIn.xlsx');
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

        // Truyền dữ thông tin
        $index = 4;
        foreach ($dataForListCashInStatical as $key => $value){
            $sheet->setCellValue('A'.$index, $value['customer_code']);
            $sheet->setCellValue('B'.$index, $value['customer_name']);
            $sheet->setCellValue('C'.$index, number_format($value['balance_previous_month']));
            $sheet->setCellValue('D'.$index, number_format($value['receivable_this_month']));
            $sheet->setCellValue('E'.$index, number_format($value['total_account_receivable']));
            $sheet->setCellValue('F'.$index, number_format($value['total_cash_in_of_current_month']));
            $sheet->setCellValue('G'.$index, number_format($value['total_cash_in_current']));

            $index ++;
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=入金情報一覧" .".xlsx");
        header("Content-Transfer-Encoding: binary ");
        $writer = new Xlsx($spreadsheet);
        ob_get_contents();
        ob_end_clean();
        $writer->save('php://output');
        die();
    }

    public function getCashInStatical($request,$id){
        $customer = Customer::find($id);
        if ($customer == null){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY , trans('errors.attribute_not_found',[
                "attribute"=> "customer",
                "id"=> $id,
            ]));
        }

        $cashInStatical = CashInStatical::
        where("customer_id",$id)
        ->where("month_line",$request->month_year)->first();

        // Nếu không tìm thấy tháng này thì tạo
        if ($cashInStatical == null){
            try {
                DB::beginTransaction();
                $this->saveCashInStaticMonth($id,$request->month_year);
                DB::commit();
            } catch (\Exception $exception){
                DB::rollBack();
                return $exception;
            }
        }

        // Kiểm tra xem có final tháng trước để cho xem tháng này
        $monthPrev = Carbon::parse($request->month_year)->subMonth()->format('Y-m'); // Lấy tháng trước
        $checkFinalMonthPrev = FinalClosingHistories::where("month_year",$monthPrev)->first();
        $checkFinal = false;
        // Kiểm tra xem tháng trước có không, nếu
        if ($checkFinalMonthPrev != null){
            $checkFinal = true;
        }

        // Tạo xong thì truy vấn lại

        // Trường hợp truy vấn tháng trước đã có finnal thì lấy theo balance_previous_month không thì theo balance_temp
        if ($checkFinal){
            $cashInStatical = CashInStatical::
            select(
                "cash_in_statisticals.customer_id",
                "customers.customer_code",
                "customers.customer_name",
                "cash_in_statisticals.month_line",
                "cash_in_statisticals.balance_previous_month",
                "cash_in_statisticals.receivable_this_month",
                "cash_in_statisticals.balance_temp",
            )
                ->addSelect(DB::raw('(cash_in_statisticals.balance_previous_month + cash_in_statisticals.receivable_this_month) as total_account_receivable'))
                ->addSelect("cash_in_statisticals.total_cash_in_current")
                ->where("cash_in_statisticals.customer_id",$id)
                ->join('customers', 'customers.id', '=', 'cash_in_statisticals.customer_id')
                ->where("month_line",$request->month_year)->first();
        } else{
            $cashInStatical = CashInStatical::
            select(
                "cash_in_statisticals.customer_id",
                "customers.customer_code",
                "customers.customer_name",
                "cash_in_statisticals.month_line",
                "cash_in_statisticals.balance_previous_month",
                "cash_in_statisticals.receivable_this_month",
                "cash_in_statisticals.balance_temp",
            )
                ->addSelect(DB::raw('(cash_in_statisticals.balance_temp + cash_in_statisticals.receivable_this_month) as total_account_receivable'))
                ->addSelect("cash_in_statisticals.total_cash_in_current")
                ->where("cash_in_statisticals.customer_id",$id)
                ->join('customers', 'customers.id', '=', 'cash_in_statisticals.customer_id')
                ->where("month_line",$request->month_year)->first();
        }


        // Truy vấn tổng tiền khách đã trả trong tháng closing date

//        $getClosingDateByMonthStart = $this->getClosingDateByMonthStart($customer->closing_date,$request->month_year);
//        $getClosingDateByMonthEnd = $this->getClosingDateByMonthEnd($customer->closing_date,$request->month_year);

        $getClosingDateByMonthStart = Carbon::parse($request->month_year)->firstOfMonth()->format('Y-m-d');
        $getClosingDateByMonthEnd = Carbon::parse($request->month_year)->endOfMonth()->format('Y-m-d');

        //Truy vấn lại lấy ra tổng tiền Cash In của customer trong tháng
        $totalCashIn = CashIn::query()
            ->select("customer_id")
            ->addSelect(DB::raw('SUM(cash_in) as total_cash_in'))
            ->where("customer_id",$id)
            ->whereBetween('payment_date', [$getClosingDateByMonthStart, $getClosingDateByMonthEnd])
            ->groupBy("customer_id")->first();

        $cashInStatical->total_cash_in = $totalCashIn->total_cash_in ?? 0;

        $cashInStatical->balance_previous_month = $checkFinal ? $cashInStatical->balance_previous_month : $cashInStatical->balance_temp;
        $cashInStatical->total_cash_in_current = $checkFinal ? $cashInStatical->total_cash_in_current : strval($cashInStatical->balance_temp + $cashInStatical->receivable_this_month - $cashInStatical->total_cash_in);

        return $this->responseJson(200, new BaseResource($cashInStatical));
    }

    public function saveCashInStaticMonth($customer_id,$month_year){
        // Lưu tiền cash in cho CashInStatical
        $customer = Customer::find($customer_id);
        /*
         * Truy vấn tổng số tiền driver_code của customer có trong tháng này theo closing_date
         * */
//        $closing_dateStart = $this->getClosingDateByMonthStart($customer->closing_date,$month_year);
//        $closing_dateEnd = $this->getClosingDateByMonthEnd($customer->closing_date,$month_year);

        $closing_dateStart = Carbon::parse($month_year)->firstOfMonth()->format('Y-m-d');
        $closing_dateEnd = Carbon::parse($month_year)->lastOfMonth()->format('Y-m-d');

        $driverCourse = DriverCourse::
        select(
            "customers.id as customers_id",
        )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->where("customer_id", $customer_id)
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
            ->where("customer_id",$customer_id)
            ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
            ->groupBy("customer_id")
            ->first();
        if ($totalCashInQuery != null){
            $totalCashIn = $totalCashInQuery->total_cash_in;
        }

        // 1. Kiểm tra xem Customer này đã từng có CashInStatical chưa
        $cashInStatical = CashInStatical::
        where("customer_id",$customer_id)
            ->first();

        // 1.1 Nếu chưa từng có thì tạo CashInStatical
        if ($cashInStatical == null){
            CashInStatical::create([
                "customer_id" => $customer_id,
                "month_line" => $month_year,
                "balance_previous_month" => 0, // tiền nhận tháng trước
                "receivable_this_month" => $receivable_this_month, // tiền phải nhận tháng này
                "total_cash_in_current" => $receivable_this_month - $totalCashIn,
                "status" => 1,
            ]);

        } else{
            // Nếu đã từng có rồi thì tìm update
            // Truy vấn số tiền nợ tháng gần nhất bằng cách tìm đến toàn bộ CashInStatical theo thời gian
            // Kiểm tra xem Customer này đã có CashInStatical tháng này chưa

            $cashInStaticalPrevMonth = CashInStatical::
            where("customer_id",$customer_id)
                ->where("month_line","<",$month_year)
                ->orderBy("month_line", "desc")
                ->first();

            $balance_previous_month = 0;
            if($cashInStaticalPrevMonth){
                $balance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
            }

            $cashInThisDate = CashInStatical::
            where("customer_id",$customer_id)
                ->where("month_line",$month_year)
                ->first();

            // Nếu chưa có thì tạo
            if ($cashInThisDate == null){
                CashInStatical::create([
                    "customer_id" => $customer_id,
                    "month_line" => $month_year,
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
                where("customer_id",$customer_id)
                    ->where("month_line",">",$month_year)
                    ->orderBy("month_line", "asc")
                    ->get();
                // Nếu có thì cập nhật các bản ghi còn lại
                $update_balance_previous_month = $balance_previous_month;
                if (count($checkCashInStaticalUpdates) != 0){
                    foreach ($checkCashInStaticalUpdates as $cashInStaticalUpdate){
                        // 1. Truy vấn tổng số tiền trong theo từng tháng theo closing date
                        // 1.1 Lấy ra khoảng thời gian theo closing_date
//                        $updateStartDateByClosingDate = "";
//                        $updateEndDateByClosingDate = "";
//                        switch ($customer->closing_date){
//                            case 1:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."16";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."15";
//                                break;
//                            case 2:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."21";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."20";
//                                break;
//                            case 3:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."26";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."25";
//                                break;
//                            case 4:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->startOfMonth();
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->endOfMonth();
//                                break;
//                        }
                        $updateStartDateByClosingDate = Carbon::parse($cashInStaticalUpdate->month_line)->firstOfMonth()->format('Y-m-d');
                        $updateEndDateByClosingDate = Carbon::parse($cashInStaticalUpdate->month_line)->lastOfMonth()->format('Y-m-d');

                        // 1.2 truy vấn tổng số tiền cần nhận trong tháng cần update lại theo closing date
                        $update_receivable_this_month = 0;
                        $updateByDriverCourseTotal = DriverCourse::
                        select(
                            "customers.id as customers_id",
                        )
                            ->addSelect(\DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
                            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                            ->join('customers', 'customers.id', '=', 'courses.customer_id')
                            ->where("customer_id", $customer_id)
                            ->whereBetween('driver_courses.date', [$updateStartDateByClosingDate, $updateEndDateByClosingDate])
                            ->groupBy("customers.id")
                            ->first();
                        if ($updateByDriverCourseTotal != null){
                            $update_receivable_this_month = $updateByDriverCourseTotal->total_course_ship_fee;
                        }

                        // 1.3 truy vấn tổng số tiền phải nhận trong tháng cần update lại theo closing date
                        $update_by_total_cash_in = 0;
                        $totalCashInQuery = CashIn::
                        select("customer_id")
                            ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
                            ->where("customer_id",$customer_id)
                            ->whereBetween('payment_date', [$updateStartDateByClosingDate,$updateEndDateByClosingDate])
                            ->groupBy("customer_id")
                            ->first();
                        if ($totalCashInQuery != null){
                            $update_by_total_cash_in = $totalCashInQuery->total_cash_in;
                        }

                        // 1.4 Update lại tiền
                        $cashInStaticalUpdate->update([
                            'balance_previous_month' => $update_balance_previous_month, // tiền tháng trước
                            "receivable_this_month" => $update_receivable_this_month, // tiền phải nhận tháng này
                            'total_cash_in_current' => $update_balance_previous_month + $update_receivable_this_month - $update_by_total_cash_in,
                        ]);
                    }
                }
            }
        }
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

    public function saveCashInStatic($customer_id,$date){
        // Lưu tiền cash in cho CashInStatical
        $customer = Customer::find($customer_id);
        /*
         * Truy vấn tổng số tiền driver_code của customer có trong tháng này theo closing_date
         * */
//        $closing_dateStart = $this->getClosing_dateStart($customer->closing_date,$date);
//        $closing_dateEnd = $this->getClosing_dateEnd($customer->closing_date,$date);

        $closing_dateStart = Carbon::parse($date)->firstOfMonth()->format('Y-m-d');
        $closing_dateEnd = Carbon::parse($date)->lastOfMonth()->format('Y-m-d');

        // Vì đang theo date nên cần lấy ra closing_date cho tháng này
//        $checkMonthForThisDate = $this->checkClosing_dateForCashInStatical($customer->closing_date,$date);
        $checkMonthForThisDate = Carbon::parse($date)->format('Y-m');

        $driverCourse = DriverCourse::
        select(
            "customers.id as customers_id",
        )
            ->addSelect(DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
            ->join('customers', 'customers.id', '=', 'courses.customer_id')
            ->where("customer_id", $customer_id)
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
            ->where("customer_id",$customer_id)
            ->whereBetween('payment_date', [$closing_dateStart,$closing_dateEnd])
            ->groupBy("customer_id")
            ->first();
        if ($totalCashInQuery != null){
            $totalCashIn = $totalCashInQuery->total_cash_in;
        }

        // 1. Kiểm tra xem Customer này đã từng có CashInStatical chưa
        $cashInStatical = CashInStatical::
        where("customer_id",$customer_id)
            ->first();

        // 1.1 Nếu chưa từng có thì tạo CashInStatical
        if ($cashInStatical == null){
            CashInStatical::create([
                "customer_id" => $customer_id,
                "month_line" => $checkMonthForThisDate,
                "balance_previous_month" => 0, // tiền nhận tháng trước
                "receivable_this_month" => $receivable_this_month, // tiền phải nhận tháng này
                "total_cash_in_current" => $receivable_this_month - $totalCashIn,
                "status" => 1,
            ]);

        } else{
            // 1.2

            // Nếu đã từng có rồi thì update
            // Truy vấn số tiền nợ tháng gần nhất bằng cách tìm đến toàn bộ CashInStatical theo thời gian
            // Kiểm tra xem Customer này đã có CashInStatical tháng này chưa

            $cashInStaticalPrevMonth = CashInStatical::
            where("customer_id",$customer_id)
                ->where("month_line","<",$checkMonthForThisDate)
                ->orderBy("month_line", "desc")
                ->first();

            $balance_previous_month = 0;
            if($cashInStaticalPrevMonth){
                $balance_previous_month = $cashInStaticalPrevMonth->total_cash_in_current;
            }

            $cashInThisDate = CashInStatical::
            where("customer_id",$customer_id)
                ->where("month_line",$checkMonthForThisDate)
                ->first();

            // Nếu chưa có thì tạo
            if ($cashInThisDate == null){
                CashInStatical::create([
                    "customer_id" => $customer_id,
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
                where("customer_id",$customer_id)
                    ->where("month_line",">",$checkMonthForThisDate)
                    ->orderBy("month_line", "asc")
                    ->get();
                // Nếu có thì cập nhật các bản ghi còn lại
                $update_balance_previous_month = $cashInThisDate->total_cash_in_current;
                if (count($checkCashInStaticalUpdates) != 0){
                    foreach ($checkCashInStaticalUpdates as $cashInStaticalUpdate){
                        // 1. Truy vấn tổng số tiền trong theo từng tháng theo closing date
                        // 1.1 Lấy ra khoảng thời gian theo closing_date
//                        $updateStartDateByClosingDate = "";
//                        $updateEndDateByClosingDate = "";
//                        switch ($customer->closing_date){
//                            case 1:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."16";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."15";
//                                break;
//                            case 2:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."21";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."20";
//                                break;
//                            case 3:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->subMonth()->format("Y-m")."26";
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->format("Y-m")."25";
//                                break;
//                            case 4:
//                                $updateStartDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->startOfMonth();
//                                $updateEndDateByClosingDate = Carbon::createFromFormat('Y-m', $cashInStaticalUpdate->month_line)->endOfMonth();
//                                break;
//                        }
                        $updateStartDateByClosingDate = Carbon::parse($cashInStaticalUpdate->month_line)->firstOfMonth()->format('Y-m-d');
                        $updateEndDateByClosingDate = Carbon::parse($cashInStaticalUpdate->month_line)->lastOfMonth()->format('Y-m-d');

                        // 1.2 truy vấn tổng số tiền cần nhận trong tháng cần update lại theo closing date
                        $update_receivable_this_month = 0;
                        $updateByDriverCourseTotal = DriverCourse::
                        select(
                            "customers.id as customers_id",
                        )
                            ->addSelect(\DB::raw('SUM(courses.ship_fee) as total_course_ship_fee'))
                            ->join('courses', 'courses.id', '=', 'driver_courses.course_id')
                            ->join('customers', 'customers.id', '=', 'courses.customer_id')
                            ->where("customer_id", $customer_id)
                            ->whereBetween('driver_courses.date', [$updateStartDateByClosingDate, $updateEndDateByClosingDate])
                            ->groupBy("customers.id")
                            ->first();
                        if ($updateByDriverCourseTotal != null){
                            $update_receivable_this_month = $updateByDriverCourseTotal->total_course_ship_fee;
                        }

                        // 1.3 truy vấn tổng số tiền phải nhận trong tháng cần update lại theo closing date
                        $update_by_total_cash_in = 0;
                        $totalCashInQuery = CashIn::
                        select("customer_id")
                            ->addSelect(\DB::raw('SUM(cash_in) as total_cash_in'))
                            ->where("customer_id",$customer_id)
                            ->whereBetween('payment_date', [$updateStartDateByClosingDate,$updateEndDateByClosingDate])
                            ->groupBy("customer_id")
                            ->first();
                        if ($totalCashInQuery != null){
                            $update_by_total_cash_in = $totalCashInQuery->total_cash_in;
                        }

                        // 1.4 Update lại tiền
                        $cashInStaticalUpdate->update([
                            'balance_previous_month' => $update_balance_previous_month, // tiền tháng trước
                            "receivable_this_month" => $update_receivable_this_month, // tiền phải nhận tháng này
                            'total_cash_in_current' => $update_balance_previous_month + $update_receivable_this_month - $update_by_total_cash_in,
                        ]);
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

    ////////////////
    public function cashInStaticalTemp($request){
        // Tìm đến tất cả customer
        $customers = Customer::all();

        try {
            DB::beginTransaction();
            // Tìm đến tất cả CashInStatic trong tháng sau năm này để cập nhật lại temp
            $afterMonth = Carbon::parse($request->month_year)->addMonth()->format("Y-m");
            //Cập nhật lại tất cả cashInStatical
            foreach ($customers as $customer){
                $cashInStatic = CashInStatical::
                where("customer_id",$customer->id)
                    ->where("month_line",$request->month_year)->first();
                // Trường hợp nếu chưa có thì tạo CashInStatical

                // Cập nhật lại tiền trước rồi mới được phép cập nhật lại tiền sau cho đảm bảo
                // Cập nhật tiền tháng hiện tại
                $this->saveCashInStaticMonth($customer->id,$request->month_year);
                // Cập nhật tiền tháng sau
                $this->saveCashInStaticMonth($customer->id,$afterMonth);
            }
            // Sau khi cập nhật xong thì mới cập nhật lại CashInStatical
            $cashInStaticalUpdateTemps = CashInStatical::
                where("month_line",$afterMonth)->get();

            // Cập nhật lại cho tháng sau
            foreach ($cashInStaticalUpdateTemps as $cashInStatical){
                $cashInStatical->balance_temp = $cashInStatical->balance_previous_month;
                $cashInStatical->save();
            }
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY,'error',$exception->getMessage(),
                $exception->getMessage());
        }

        return ResponseService::responseData(CODE_SUCCESS, 'success');
    }
}
