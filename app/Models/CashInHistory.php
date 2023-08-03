<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashInHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_in_histories';

    protected $fillable = [
        "customer_id",
        "cash_in_id",
        "type",
        "cash_in",
        "payment_method",
        "payment_date",
        "note",
        "status",
        "created_at",
        "updated_at",
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
