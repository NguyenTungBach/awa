<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Models;

use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'drivers';

    protected $dates = ['deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'type',
        'driver_code',
        'driver_name',
        'start_date',
        'end_date',
        'car',
        'note',
        'status',
        'created_at',
        'updated_at'
    ];

    public function scopeSortByForDriver($query)
    {
        if (request()->filled('field') && request()->filled('sortby')) {
            $field = request()->get('field');
            $sortby = request()->get('sortby');
            $query->orderBy($field, $sortby);
        }
        return $query;
    }

}
