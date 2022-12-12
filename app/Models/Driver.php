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
    //const flag
    const DRIVER_FLAG_POSITION_LEADER = 'lead'; // leader
    const DRIVER_FLAG_POSITION_FULL_TIME = 'full';  //full time
    const DRIVER_FLAG_POSITION_PART_TIME = 'part';  //part time

    //const status
    const DRIVER_STATUS_WORK = 'on';  // driver work
    const DRIVER_STATUS_OFF = 'off';  //hearse off

    //cons time OT
    const DRIVER_TIME_WORK_DAILY = 'daily';  // driver cannot over 15h/day
    const DRIVER_TIME_WORK_MONTH = 'month';  //driver cannot OT over 40h/month


    // const table
    const DRIVER_FLAG = "flag";
    const DRIVER_CODE = "driver_code";
    const DRIVER_NAME = "driver_name";
    const DRIVER_START_DATE = "start_date";
    const DRIVER_END_DATE = "end_date";
    const DRIVER_BIRTH_DAY = "birth_day";
    const DRIVER_NOTE = "note";
    const DRIVER_WORKING_DAY = "working_day";
    const DRIVER_DAY_OF_WEEK = "day_of_week";
    const DRIVER_WORKING_TIME = "working_time";
    const DRIVER_STATUS = "status";
    const GRADE = 'grade';

    protected $fillable = [self::DRIVER_FLAG, self::DRIVER_CODE, self::GRADE, self::DRIVER_NAME, self::DRIVER_START_DATE, self::DRIVER_END_DATE, self::DRIVER_BIRTH_DAY, self::DRIVER_NOTE, self::DRIVER_WORKING_DAY,
        self::DRIVER_DAY_OF_WEEK, self::DRIVER_WORKING_TIME, self::DRIVER_STATUS];

    protected $casts = [
        'data' => 'array'
    ];

    /*  Relationships  */
    public function driverCourse()
    {
        return $this->hasMany(DriverCourse::class, 'driver_code', 'driver_code');
    }

    public function course()
    {
        return $this->belongsToMany(Course::class, 'driver_courses', 'driver_code', 'course_code');
    }

    public function dayOff()
    {
        return $this->hasMany(DayOff::class, 'driver_code', 'driver_code');
    }
    public function reports(){
        return $this->hasMany(Report::class,'driver_code','driver_code');
    }
    public function resultAI()
    {
        return $this->hasMany(ResultAI::class,'driver_code','driver_code');
    }
    public function grades() {
        return $this->hasMany(Grade::class, 'driver_code', 'driver_code');
    }
}
