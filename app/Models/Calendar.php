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
use Illuminate\Http\Response;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendars';

    CONST CALENDAR_DATE = 'date';
    CONST CALENDAR_WEEK = 'week';
    CONST CALENDAR_ROKUYOU = 'rokuyou';
    CONST CALENDAR_HOLIDAY = 'holiday';

    protected $fillable = [self::CALENDAR_DATE, self::CALENDAR_WEEK,self::CALENDAR_ROKUYOU,self::CALENDAR_HOLIDAY];

    protected $casts = [
        'data' => 'array'
    ];

    /**  create calendar
     * @param $date
     * @param null $week
     * @param null $rokuyou
     * @param null $holiday
     */
    public static function create($date,$week = null ,$rokuyou = null ,$holiday = null){
        $calendar = new Calendar();
        $calendar->date = $date;
        $calendar->week = $week;
        $calendar->rokuyou = $rokuyou;
        $calendar->holiday = $holiday;
        $r = $calendar->save();
        if (!$r){
            return ResponseService::responseData(Response::HTTP_BAD_REQUEST,'error','errors');
        }
        return ResponseService::responseData(Response::HTTP_OK,'success','success',$r);
    }
}
