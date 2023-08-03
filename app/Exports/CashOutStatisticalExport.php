<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Repository\CashOutStatisticalRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Models\Calendar;

class CashOutStatisticalExport implements FromView
{
    protected array $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function view(): View
    {
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $startDateJapan = Calendar::where('date', $startOfMonth)->first()->week;
        $endDateJapan = Calendar::where('date', $endOfMonth)->first()->week;

        $startDate = Carbon::now()->startOfMonth()->format('Y年m月d日');
        $endDate = Carbon::now()->endOfMonth()->format('Y年m月d日');

        $title = $startDate.'('.$startDateJapan.')'.'〜'.$endDate.'('.$endDateJapan.')';
        return view('exports.cash_out_statistical', [
            'result' => CashOutStatisticalRepository::getAllCashOutStatisticalByDriver($this->input),
            'title' => $title,
        ]);
    }
}
