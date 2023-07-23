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

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courses';

    //const flag
    const COURSE_FLAG_YES = 'yes'; //Course run
    const COURSE_FLAG_NO = 'no'; //Course run
    const COURSE_FLAG_NULL = null;  //Course null

    //const status
    const COURSE_STATUS_WORK = 'on';  // Course work
    const COURSE_STATUS_OFF = 'off';  //Course off

    protected $fillable = [
        'customer_id',
        'course_name',
        'ship_date',
        'start_date',
        'end_date',
        'break_time',
        'departure_place',
        'arrival_place',
        'ship_fee',
        'associate_company_fee',
        'expressway_fee',
        'commission',
        'meal_fee',
        'note',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    // public function coursePatterns()
    // {
    //     return $this->hasMany(CoursePattern::class, 'course_parent_code', 'course_code');
    // }

    // /*  Relationships  */
    // public function driverCourse(){
    //     return $this->hasMany(DriverCourse::class,'course_code','course_code');
    // }
    // public function owner(){
    //     return $this->hasOne(DriverCourse::class,'course_code','course_code')->where('is_checked','yes');
    // }
    // public function driver(){
    //     return $this->belongsToMany(Driver::class,'driver_courses','course_code','driver_code');
    // }

    // public function courseSchedules()
    // {
    //     return $this->hasMany(CourseSchedule::class, 'course_code', 'course_code');
    // }

    // public function resultAI()
    // {
    //     return $this->hasMany(ResultAI::class, 'result_ai', 'course_code');
    // }

    // public function points()
    // {
    //     return $this->hasMany(Point::class, 'course_code', 'course_code');
    // }
}
