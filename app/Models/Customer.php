<?php
/**
 * Created by VeHo.
 * Year: 2023-07-20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'customer_code',
        'customer_name',
        'closing_date',
        'person_charge',
        'post_code',
        'address',
        'phone',
        'note',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
