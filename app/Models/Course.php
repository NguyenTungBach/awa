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

    public static $configDataCourseUnique = [
        [
            'flag' => 'no',
            'course_code' => '00001',
            'course_name' => 'Course 1',
            'start_time' => '06:15',
            'end_time' => '19:45',
            'break_time' => '1.50',
            'start_date' => '2022-09-01',
            'point' => 0,
        ],
        [
            'flag' => 'no',
            'course_code' => '00002',
            'course_name' => 'Course 2',
            'start_time' => '06:00',
            'end_time' => '19:30',
            'break_time' => '1.50',
            'start_date' => '2022-09-01',
            'point' => 1,
        ]
    ];

    //const flag
    const COURSE_FLAG_YES = 'yes'; //Course run
    const COURSE_FLAG_NO = 'no'; //Course run
    const COURSE_FLAG_NULL = null;  //Course null

    //const status
    const COURSE_STATUS_WORK = 'on';  // Course work
    const COURSE_STATUS_OFF = 'off';  //Course off

    // const table
    const COURSE_FLAG = "flag";
    const COURSE_POT = "pot";
    const COURSE_CODE = "course_code";
    const COURSE_NAME = "course_name";
    const COURSE_START_TIME = "start_time";
    const COURSE_END_TIME = "end_time";
    const COURSE_BREAK_TIME = "break_time";
    const COURSE_START_DATE = "start_date";
    const COURSE_END_DATE = "end_date";
    const COURSE_STATUS = "status";
    const COURSE_NOTE = "note";
    const COURSE_GROUP = "group";
    const COURSE_POINT = "point";

    protected $fillable = [self::COURSE_FLAG, self::COURSE_POT, self::COURSE_CODE,self::COURSE_NAME,self::COURSE_START_TIME,self::COURSE_END_TIME,self::COURSE_BREAK_TIME,self::COURSE_START_DATE,self::COURSE_END_DATE,self::COURSE_STATUS,self::COURSE_NOTE,self::COURSE_GROUP,self::COURSE_POINT];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function coursePatterns()
    {
        return $this->hasMany(CoursePattern::class, 'course_parent_code', 'course_code');
    }

    /*  Relationships  */
    public function driverCourse(){
        return $this->hasMany(DriverCourse::class,'course_code','course_code');
    }
    public function owner(){
        return $this->hasOne(DriverCourse::class,'course_code','course_code')->where('is_checked','yes');
    }
    public function driver(){
        return $this->belongsToMany(Driver::class,'driver_courses','course_code','driver_code');
    }

    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class, 'course_code', 'course_code');
    }

    public function resultAI()
    {
        return $this->hasMany(ResultAI::class, 'result_ai', 'course_code');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'course_code', 'course_code');
    }
}
