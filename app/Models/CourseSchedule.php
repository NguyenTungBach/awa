<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSchedule extends Model
{
    use HasFactory;

    const TABLE_NAME = 'course_schedules';

    protected $table = self::TABLE_NAME;

    //const status
    const COURSE_STATUS_WORK = 'on';
    const COURSE_STATUS_OFF = 'off';

    // const table
    const SCHEDULE_CODE = 'course_code';
    const SCHEDULE_NAME = 'course_name';
    const SCHEDULE_STATUS = 'status';
    const SCHEDULE_DATE = 'schedule_date';
    const SCHEDULE_LUNAR_JP = 'lunar_jp';

    protected $fillable = [
        self::SCHEDULE_CODE,
        self::SCHEDULE_NAME,
        self::SCHEDULE_STATUS,
        self::SCHEDULE_DATE,
        self::SCHEDULE_LUNAR_JP,
    ];


    protected $dates = [
        //'deleted_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

}
