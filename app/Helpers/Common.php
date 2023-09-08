<?php


namespace Helper;


use App\Models\Calendar;
use App\Models\Course;
use App\Models\DayOff;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\App;


class Common
{
    public static function getTextType($type): string
    {
        $role = Auth::user()->role;
        $text = '';
        if (in_array($type, DAY_OFF_CODE)) {
            if ($role != 'admin') {
                return '休日';
            }
            switch ($type) {
                case IS_HOLIDAY:
                    $text = JP_HOLIDAY;
                    break;
                case IS_FIX_DAY_OFF:
                    $text = JP_FIX_DAY_OFF;
                    break;
                case IS_DAY_OFF_REQUEST:
                    $text = JP_DAY_OFF_REQUEST;
                    break;
                case IS_DAY_OFF_PAID:
                    $text = JP_DAY_OFF_PAID;
                    break;
            }
        } elseif (in_array($type, WAIT_CODE)) {
            switch ($type) {
                case IS_LEADER:
                    $text = JP_LEADER;
                    break;
                case IS_WAIT:
                    $text = JP_WAIT;
                    break;
                case IS_WAIT_BETWEEN_TASKS:
                    $text = JP_WAIT_BETWEEN_TASKS;
                    break;
            }
        } else {
            $text = $type;
        }

        return $text;
    }

    public static function getColorType($type): string
    {
        $role = Auth::user()->role;
        if ($role != 'admin' && in_array($type, DAY_OFF_CODE)) {
            return COLOR_FIXED_DAY_OFF;
        }
        switch ($type) {
            case IS_HOLIDAY :
                return COLOR_HOLIDAY;
            case IS_FIX_DAY_OFF :
                return COLOR_FIXED_DAY_OFF;
            case IS_DAY_OFF_REQUEST :
                return COLOR_DAY_OFF_REQUEST;
            case IS_DAY_OFF_PAID :
                return COLOR_PAID;
            default:
                return COLOR_WORK;
        }
    }

    public static function Calendars($startDate = '', $endDate = '')
    {
        if (!strtotime($startDate)) {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
        }
        if (!strtotime($endDate)) {
            $endDate = Carbon::parse($startDate)->endOfMonth()->toDateString();
        }
        $result = Calendar::select('date', 'week', 'rokuyou', 'holiday')
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();
        return $result->keyBy('date')->toArray();
    }

    public static function uploadFile(UploadedFile $file, $path = '', $userId = null)
    {
        $userId = $userId ?? Auth::id();
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $fileName);
    }

    public static function randNotInArr($min, $max, $inArray)
    {
        do {
            $rand = rand($min, $max);
        } while (in_array($rand, $inArray));
        return $rand;
    }

    /**
     * Change input encoding for by file csv, xlsx
     *
     *
     * @param $path : string path to file check encoding
     */
    public static function changeInputEncodingByFile($pathFile = null, $encChange = null)
    {
        $enc = $encChange ? $encChange : 'UTF-8';
        if ($pathFile) {
            $enc = self::getEncodingByFile($pathFile);
        }
        \Config::set('excel.imports.csv.input_encoding', $enc);
    }

    public static function getEncodingByFile($pathFile)
    {
        return mb_detect_encoding(file_get_contents($pathFile), mb_list_encodings(), true);
    }

    public static function onWindows()
    {
        return PHP_OS === 'WINNT' || Str::contains(php_uname(), 'Microsoft');
    }

    public static function sendNotificationFCM($notification_id, $title, $message, $id, $type)
    {
        $accesstoken = env('FCM_KEY');
        $URL = 'https://fcm.googleapis.com/fcm/send';
        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },
          }';

        $crl = curl_init();
        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: ' . $accesstoken;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        $rest = curl_exec($crl);

        if ($rest === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {
            $result_noti = 1;
        }

        //curl_close($crl);
        //print_r($result_noti);die;
        return $result_noti;
    }

    /**
     * @param $time
     * @return string
     */
    public static function convertFormartTime($time)
    {
        $arrayTime = explode('-', $time);
        $timeConvert = (int)$arrayTime[1] . "/" . (int)$arrayTime[2] . "/" . (int)$arrayTime[0];
        return $timeConvert;
    }

    /**
     * @param $driver
     * @param $day
     * @return bool
     */
    public static function getDataDayOfWeek($driver, $day)
    {
        $dayOfWeek = $driver['day_of_week'];
        if (!$dayOfWeek) return false;
        if (strpos($dayOfWeek, $day) !== false) {
            return true;
        }
        return false;
    }

    /**
     * @param $driver
     * @param $type
     * @return bool
     */
    public static function getDataOverTime($driver, $type)
    {
        $dayWorkingTime = $driver['working_time'];
        if (!$dayWorkingTime) return false;
        if (strpos($dayWorkingTime, $type) !== false) {
            return true;
        }
        return false;
    }

    /**
     * @param $code
     * @return string|null
     */
    public static function getTextWithCodeDayoff($code)
    {
        $result = str_replace('-', '_', $code);
        switch ($result) {
            case "D_1" :
                return D_1;
            case "D_2" :
                return D_2;
            case "D_3" :
                return D_3;
            case "D_4" :
                return D_4;
            default:
                return null;
        }
    }

    /**
     * @param $time
     * @return string
     */
    public static function convertTimeForAi($time)
    {
        $arrayTime = explode(':', $time);
//        $newTime = (int)$arrayTime[0].":".$arrayTime[1];
        $noonLondonTz = Carbon::createFromTime((int)$arrayTime[0], $arrayTime[1], 0)->toTimeString();
        return $noonLondonTz;
    }

    /**
     * @param $time
     * @return mixed|string
     */
    public static function convertBreakTimeForAI($time)
    {
        if (is_int($time)) return $time . ".00";
        $arrayTime = explode('.', $time);
        if (strlen($arrayTime[1]) == 1) {
            return $time . "0";
        }
        return $time;
    }

    public static function getConfigURLFileAI($ype)
    {
        switch ($ype) {
            case 'custom_folder_input_local':
                return self::getURLAIInput();
            case 'custom_download_output_local':
                return self::getURLAIOutput();
            default :
                return self::getURLAIOutput();
        }
    }

    private static function getURLAIInput()
    {
        $production = env('APP_ENV');
        switch ($production) {
            case 'dev' :
                return URL_DEV_INPUT;
            case 'staging' :
                return URL_STAGING_INPUT;
            case 'production' :
                return URL_PRODUCTION_INPUT;
            default :
                return URL_LOCAL_INPUT;

        }
    }

    private static function getURLAIOutput()
    {
        $production = env('APP_ENV');
        switch ($production) {
            case 'dev' :
                return URL_DEV_OUTPUT;
            case 'staging' :
                return URL_STAGING_OUTPUT;
            case 'production' :
                return URL_PRODUCTION_OUTPUT;
            default :
                return URL_LOCAL_OUTPUT;
        }
    }

    public static function convertDataCodeInput($code, $number)
    {
        $addChar = null;
        $legthCode = strlen($code);
        $missingCharacter = $number - $legthCode;
        if ($missingCharacter == 0) return $code;
        for ($i = 1; $i <= $missingCharacter; $i++) {
            $addChar .= '0';
        }
        return $addChar . $code;
    }

    public static function checkTimeCourse($value, $type)
    {

        switch ($type) {
            case 'time':
                $arrayValueTime = explode(':', $value);
                if ((int)$arrayValueTime[0] > 31 || $arrayValueTime[1] % 15 != 0) {
                    return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.time.Invalid_time'));
                }
                return ResponseService::responseData(Response::HTTP_OK, 'success', '');
            case 'break':
                $arrayValueTime = explode('.', $value);
                if (!is_numeric($arrayValueTime[0]) || $arrayValueTime[0] < 0 || is_numeric($arrayValueTime[0]) > 31) {
                    return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.time.Invalid_time'));
                }
                if ($arrayValueTime[1] % 25 != 0 || $arrayValueTime[1] > 75) {
                    return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.time.Invalid_time'));
                }
                return ResponseService::responseData(Response::HTTP_OK, 'success', '');
            default:
                return ResponseService::responseData(Response::HTTP_OK, 'success', '');
        }

    }

    /**
     * @param $driver
     * @param $date
     * @return bool
     *
     */
    public static function convertListDriverStartDate($driver, $date)
    {
        $startDate = Carbon::parse($driver->start_date)->format('Y-m');
        if (strtotime($startDate) <= strtotime($date)) {
            return false;
        }
        return true;
    }

    /**
     * @param $driver
     * @param $date
     * @return bool
     */
    public static function convertListDriverEndDate($driver, $date)
    {
        if ($driver->end_date) {
            $endDate = Carbon::parse($driver->end_date)->addMonth(0)->format('Y-m');
            if (strtotime($endDate) >= strtotime($date)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public static function getPathAI()
    {
        $production = App::environment();
        switch ($production) {
            case 'dev' :
                return PATH_URL_DEV_AI;
            case 'staging' :
                return PATH_URL_STAGING_AI;
            case 'production' :
                return PATH_URL_PRODUCTION_AI;
            default :
                return PATH_LOCAL_AI;
        }
    }

    /**
     * @return string
     */
    public static function getEnvironmentAI()
    {
        $production = App::environment();
        switch ($production) {
            case 'dev' :
                return PATH_ENVIRONMENT_URL_DEV_AI;
            case 'staging' :
                return PATH_ENVIRONMENT_URL_STAGING_AI;
            case 'production' :
                return PATH_ENVIRONMENT_URL_PRODUCTION_AI;
            default :
                return PATH_ENVIRONMENT_LOCAL_AI;
        }
    }

    /**
     * @param $request
     * @param $data
     * @return mixed
     */
    public static function pagination($request, $data)
    {
        if ($request->per_page < 0) {
            $object = $data->get()->count();
            return $data->paginate($object);
        } else {
            $limit = is_null(request('per_page')) ? config('repository.pagination.limit', 15) : request('per_page');
            return $data->paginate($limit);
        }
    }

    public static function checkValidateShift($driver_id,$date){
        $driver = Driver::find($driver_id);
        // 1. Kiểm tra ngày đó có rơi vào final_closing không?
        $getMonthYear = Carbon::parse($date)->format('Y-m');
        $checkFinalClosingHistories = FinalClosingHistories::where('month_year',$getMonthYear)
            ->exists();
        // Nếu có tồn tại (không là duy nhất)
        if ($checkFinalClosingHistories){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY,'error',
                trans("errors.final_closing_histories" ,[
                    "attribute"=> "course"
                ]),[
                    "attribute"=> "course"
                ]);
        }
//        // 2. Kiểm tra ngày đó có rơi vào ngày nghỉ của driver không?
//        $course = DriverCourse::
//            where('driver_id',$driver_id)
//            ->where('date',$date)
//            ->whereIn('course_id',DriverCourse::ALL_ID_SPECIAL)
//            ->first();
//        if ($course){
//            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY,'error',
//                trans('errors.driver_in_day_off'),[
//                    "driver_id"=> $driver->id,
//                    "driver_name"=> $driver->driver_name,
//                    "course_id"=> $course->id,
//                    "course_name"=> $course->course_name,
//                    "date"=> $date,
//                ]);
//        }
        // 3. Kiểm tra driver đó có nghỉ hưu chưa?
        if ($driver->end_date != null){
            $dateRetirement = Carbon::parse($driver->end_date);
            $checkDate = Carbon::parse($date);
            // Nếu ngày chọn đã đến hoặc qua ngày nghỉ hưu thì báo lỗi
            if ($checkDate->gte($dateRetirement)){
                return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY,'error',
                    trans("errors.end_date_retirement" ,[
                        "attribute"=> "driver $driver->driver_name",
                        "end_date"=> $dateRetirement->format('Y-m-d')
                    ]),
                    [
                        "driver_id"=> "driver_id: $driver->id",
                        "driver_name"=> "driver_name: $driver->driver_name",
                        "end_date"=> $dateRetirement->format('Y-m-d')
                    ]);
            }
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success');
    }
}
