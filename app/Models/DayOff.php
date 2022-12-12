<?php
/**
 * Created by VeHo.
 * Year: 2022-08-04
 */

namespace App\Models;

use Helper\ResponseService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DayOff extends Model
{
    use HasFactory;

    protected $table = 'day_offs';


    const DAY_OFF_TYPE_HOLIDAY = 'D-1'; // Ngày nghỉ
    const DAY_OFF_TYPE_FIXED_DAY_OFF = 'D-2'; // Ngày nghỉ theo lịch đăng ký
    const DAY_OFF_TYPE_DAY_OFF_REQUEST = 'D-3'; //Ngày nghỉ , or ngày chưa di lam
    const DAY_OFF_TYPE_PAID = 'D-4'; // ngày nghỉ có lương
    const DAY_OFF_TYPE_WORK = 'D-5'; // ngày di lam


    const DAY_OFF_DRIVER_CODE = 'driver_code';
    const DAY_OFF_DRIVER_NAME = 'driver_name';
    const DAY_OFF_DATE = 'date';
    const DAY_OFF_TYPE = 'type';
    const DAY_OFF_HAS_CODES = 'has_codes';
    const DAY_OFF_COLOR = 'color';
    const DAY_OFF_LUNAR_JP = 'lunar_jp';

    protected $fillable = [self::DAY_OFF_DRIVER_CODE,self::DAY_OFF_DRIVER_NAME,self::DAY_OFF_DATE,self::DAY_OFF_TYPE, self::DAY_OFF_HAS_CODES, self::DAY_OFF_COLOR,self::DAY_OFF_LUNAR_JP];


    protected $casts = [
        'data' => 'array'
    ];


    public function createDayOff(Driver $driver, $date, $type, $color,$lunarJp, $course_code = '')
    {
        try {
            $dayOff = new DayOff();
            $dayOff->driver_code = $driver->driver_code;
            $dayOff->driver_name = $driver->driver_name;
            $dayOff->date = $date;
            $dayOff->type = $type;
            $dayOff->has_codes = $course_code;
            $dayOff->color = $color;
            $dayOff->lunar_jp = $lunarJp?json_encode($lunarJp):null;
            $r = $dayOff->save();
            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $dayOff);
        } catch (\Exception $exception) {
            Log::error('Dayoff create' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
        }
    }

    public function updateDayOff(DayOff $dayOff, $date = null, $type = null, $color = null,$lunarJp = null)
    {
        try {
            if ($date) $dayOff->date = $date;
            if ($type) $dayOff->type = $type;
            if ($color) $dayOff->color = $color;
            if ($lunarJp) $dayOff->lunar_jp = $lunarJp?json_encode($lunarJp):null;
            $r = $dayOff->save();
            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $dayOff);
        } catch (\Exception $exception) {
            Log::error('Dayoff update' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
        }
    }

}




