<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashOutHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_out_histories';

    protected $fillable = [
        'driver_id',
        'cash_out_id',
        'type',
        'cash_out',
        'payment_method',
        'payment_date',
        'note',
    ];
}
