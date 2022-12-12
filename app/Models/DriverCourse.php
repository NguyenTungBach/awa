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

    const DRIVER_COURSE_DRIVER_CODE = 'driver_code';
    const DRIVER_COURSE_COURSE_CODE = 'course_code';
    const DRIVER_COURSE_COURSE_IS_CHECKED = 'is_checked';

    protected $table = 'driver_courses';


    protected $fillable = [self::DRIVER_COURSE_COURSE_CODE,self::DRIVER_COURSE_DRIVER_CODE, self::DRIVER_COURSE_COURSE_IS_CHECKED];


    protected $casts = [
        'data' => 'array'
    ];



    /*  Relationships  */
    public function driver(){
        return $this->belongsTo(Driver::class,'driver_code','driver_code');
    }
    public function course(){
        return $this->belongsTo(Course::class,'course_code','course_code');
    }


    /**
     * @param Driver $driver
     * @param $courseCode
     * @param $isChecked
     * @return array|mixed|null
     */
    public function createDriverCourse(Driver $driver, $courseCode, $isChecked)
    {
        $checkValidateCreateDriverCourse = self::checkValidateDriverCourse($driver, $courseCode);
        if ($checkValidateCreateDriverCourse['status'] != 'success') {
            return ResponseService::responseData($checkValidateCreateDriverCourse['code'], $checkValidateCreateDriverCourse['status'], $checkValidateCreateDriverCourse['message']);
        }
        try {
            $driverCourse = new DriverCourse();
            $driverCourse->driver_code = $driver->driver_code;
            $driverCourse->course_code = $courseCode;
            $driverCourse->is_checked = $isChecked;
            $r = $driverCourse->save();
            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $driverCourse);
        } catch (\Exception $exception) {
            Log::error('driver_course create' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
        }
    }


    // check base key validate
    private function checkValidateDriverCourse($driver, $courseCode)
    {
        $checkCourse = DriverCourse::where('driver_code',$driver->driver_code)->where('course_code',$courseCode)->first();
        if ($checkCourse){
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.data_already_extists'));
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }



}
