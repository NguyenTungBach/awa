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
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function course()
    {
        return $this->hasMany(Course::class, 'customer_id');
    }

    public function scopeSortByForCustomer($query)
    {
        if (request()->filled('field') && request()->filled('sortby')) {
            $field = request()->get('field');
            $sortby = request()->get('sortby');
            $query->orderBy($field, $sortby);
        }
        return $query;
    }
}
