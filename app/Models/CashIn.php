<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashIn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_ins';

    protected $fillable = [
        "customer_id",
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

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id')
            ->select('id', 'customer_code', 'customer_name','closing_date', 'person_charge', 'post_code', 'address', 'phone', 'status');
    }
}
