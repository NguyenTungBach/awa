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

    const ALL_ID_SPECIAL = [1,2,3,4,5,6,7];
    const ID_CHILL = [1,4,5,6,7];
    const ID_PERSONAL_WORK = [2,3];

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
            ->select('courses.id as id',
                'courses.customer_id as customer_id',
                'customers.customer_name as customer_name',
                'courses.course_name as course_name',
                'courses.ship_date as ship_date',
                'courses.start_date as start_date', 'courses.break_time as break_time', 'courses.end_date as end_date',
                'courses.departure_place as departure_place',
                'courses.arrival_place as arrival_place',
                'courses.ship_fee as ship_fee',
                'courses.associate_company_fee as associate_company_fee',
                'courses.expressway_fee as expressway_fee',
                'courses.commission as commission',
                'courses.meal_fee as meal_fee',
                'courses.status as status',
            )->leftJoin('customers', 'courses.customer_id', '=', 'customers.id');
    }

}
