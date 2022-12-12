<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Requests;

use App\Models\DriverCourse;
use App\Models\User;
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
                default:
                    return [];
          }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [

            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            $flag = \Illuminate\Http\Request::all()['flag'];
            if ($flag != 'delete'){
                return  [
                    "driver_id" => "required",
                    "driver_course" => "required",
                    "flag" => ['required',Rule::in(['delete','update'])],
                ];
            }else{
                return  [

                ];
            }
        }
     }

    public function messages()
    {
        $flag = \Illuminate\Http\Request::all()['flag'];
        if ($flag != 'delete'){
            return [
                'required' => trans('validation.required'),
                'in' => trans('validation.in'),
                'in_array' => trans('validation.in_array')
            ];
        }else{
            return  [

            ];
        }

    }
}
