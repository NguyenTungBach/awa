<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Repository\PaymentRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Models\Calendar;
use Illuminate\Support\Arr;

class PaymentExport implements FromView
{
    protected array $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function view(): View
    {
        $this->input['order_by'] = Arr::get($this->input, 'order_by', 'drivers.id');
        $this->input['sort_by'] = Arr::get($this->input, 'sort_by', 'desc');
        // $this->input['month_year'] = Arr::get($this->input, 'month_year', 'drivers.id');
        dd($this->input);
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $startDateJapan = Calendar::where('date', $startOfMonth)->first()->week;
        $endDateJapan = Calendar::where('date', $endOfMonth)->first()->week;

        $startDate = Carbon::now()->startOfMonth()->format('Y年m月d日');
        $endDate = Carbon::now()->endOfMonth()->format('Y年m月d日');
        dd(1);

        $title = $startDate.'('.$startDateJapan.')'.'〜'.$endDate.'('.$endDateJapan.')';
        return view('exports.cash_out_statistical', [
            'result' => PaymentRepository::getAll($this->input),
            'title' => $title,
        ]);
    }
}
