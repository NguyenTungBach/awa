<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Requests;

use App\Models\DriverCourse;
use App\Models\User;
use App\Rules\DriverCourseUniqueRule;
use App\Rules\ShipDateRule;
use App\Rules\TimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class DriverCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
          switch (Route::getCurrentRoute()->getActionMethod()){
                case 'update':
                    return $this->getCustomRule();
                case 'store':
                    return $this->getCustomRule();
              case 'index':
                  return $this->getCustomRule();
              case 'total_extra_cost':
                  return $this->getCustomRule();
              case 'export_shift':
                  return $this->getCustomRule();
                default:
                    return [];
          }
    }

     public function getCustomRule(){
         if(Route::getCurrentRoute()->getActionMethod() == 'index'){
             return [
                 "closing_date" => [
                     "in:24,25",
                 ],
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
                 "field" => "in:drivers.driver_code,drivers.type,drivers.driver_name",
                 "sortby" => "in:asc,desc"
             ];
         }
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [

            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return [
                'driver_id' => [
                    'required',
                    Rule::exists('drivers', 'id'),
//                    new DriverCourseUniqueRule("date","driver_id","course_id"),
                ],
                "items"=> [
                    'required',
                ],
                'items.*.course_id' => [
                    'required',
                    Rule::exists('courses', 'id'),
//                    new DriverCourseUniqueRule("date","driver_id","course_id"),
                ],
                "items.*.date" => [
                    'required',
                    'date_format:Y-m-d',
//                    ,new DriverCourseUniqueRule("date","driver_id","course_id"),
                    ],
                "items.*.start_time" => [
                    "required",
                    'date_format:H:i',
                    new TimeRule("start_time")
                ],
                "items.*.break_time" => [
                    "required",
                    'date_format:H:i',
                    new TimeRule("break_time")
                ],
                "items.*.end_time" => [
                    "required",
                    'date_format:H:i',
                    'after_or_equal:items.*.start_time',
                    new TimeRule("end_time")
                ],
            ];
        }

         if(Route::getCurrentRoute()->getActionMethod() == 'total_extra_cost'){
             return [
                 "closing_date" => [
                     'required',
                     "in:24,25",
                 ],
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
             ];
         }

         if(Route::getCurrentRoute()->getActionMethod() == 'export_shift'){
             return [
                 "closing_date" => [
                     'required',
                     "in:24,25",
                 ],
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
                 "field" => "in:drivers.driver_code,drivers.type,drivers.driver_name",
                 "sortby" => "in:asc,desc"
             ];
         }
     }

    public function messages()
    {
        return [
            'required' => trans('validation.required'),
            'driver_id.exists' => "driver_id not found in database",
            'items.*.course_id.exists' => "course_id not found in database",
            'sortby.in' => 'Please input asc or desc',
            'closing_date.in' => 'Please input 24 or 25',
        ];
    }
}
