<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CashOutStatistical;
use App\Models\DriverCourse;
use Carbon\Carbon;

class CreateCashOutStatistical extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:cashOutStatistical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create cash out statistical does not exist this month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = 0;
        $month_now = Carbon::now()->format('Y-m');
        $cashOuts = CashOutStatistical::get();

        $arr1 = [];
        $arr2 = [];
        foreach ($cashOuts as $key => $value) {
            $arr2[$value->driver_id] = $value->month_line;
            $arr1[$value->driver_id] = $month_now;
        }

        // so sanh arr1 va arr 2, ket qua tra ra la cai driver chua duoc tao trong thang nay
        $items = array_diff_assoc($arr1, $arr2);
        foreach ($items as $key => $item) {
            // lay ra driver theo key va theo thang duoc tao gan nhat voi thang hien tai
            $statisOut = CashOutStatistical::where('driver_id', $key)->orderBy('month_line', 'desc')->first();
            $result = CashOutStatistical::create([
                'driver_id' => $statisOut->driver_id,
                'month_line' => $item,
                'balance_previous_month' => ($statisOut->balance_previous_month + $statisOut->payable_this_month - $statisOut->total_cash_out_current), // ( balance_previous_month + payable_this_month - total_cash_out_current )
                'payable_this_month' => 00.00, // 00.00
                'total_cash_out_current' => 00.00, // 00.00
            ]);
        }

        if ($result) {
            $result = 1;
        }

        return $result;
    }
}
