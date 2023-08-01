<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashInStatical extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_in_statisticals';

    protected $fillable = [
        "customer_id",
        "month_line",
        "balance_previous_month",
        "receivable_this_month",
        "total_cash_in_current",
        "status",
        "created_at",
        "updated_at",
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
