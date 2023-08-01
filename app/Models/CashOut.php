<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashOut extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_outs';

    protected $fillable = [
        'driver_id',
        'cash_out',
        'payment_method',
        'payment_date',
        'note',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }
}
