<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashOutStatistical extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_out_statisticals';

    protected $fillable = [
        'driver_id',
        'month_line',
        'balance_previous_month',
        'payable_this_month',
        'total_cash_out_current',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }
}
