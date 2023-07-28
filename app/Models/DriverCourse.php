<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Helper\ResponseService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DriverCourse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'driver_courses';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "driver_id",
        "course_id",
        "start_time",
        "end_time",
        "break_time",
        "date",
        "status",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function scopeSortByForDriverCourse($query)
    {
        if (request()->filled('field') && request()->filled('sortby')) {
            $field = request()->get('field');
            $sortby = request()->get('sortby');
            $query->orderBy($field, $sortby);
        }
        return $query;
    }

    /*  Relationships  */
    public function driver(){
        return $this->belongsTo(Driver::class,'driver_id','id')
            ->select('id', 'type', 'driver_code','type', 'driver_name', 'start_date', 'end_date', 'car', 'status');
    }
    public function course(){
        return $this->belongsTo(Course::class,'course_id','id')
            ->select('id',
                'customer_id',
                'course_name',
                'ship_date',
                'start_date', 'break_time', 'end_date',
                'departure_place',
                'arrival_place',
                'ship_fee',
                'associate_company_fee',
                'expressway_fee',
                'commission',
                'meal_fee',
                'status',
            );
    }

}
