<?php
/**
 * Created by VeHo.
 * Year: 2022-08-04
 */

namespace App\Http\Requests;

use App\Models\DayOff;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class DayOffRequest extends FormRequest
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
                case 'store':
                    return $this->getCustomRule();
                case 'index':
                    return $this->getCustomRule();
                default:
                    return [];
          }
    }

    public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            $arrayConfigType = array_merge(DAY_OFF_CODE, [DayOff::DAY_OFF_TYPE_WORK]);
            $request = \Illuminate\Http\Request::all();
            return  [
                "date" => "required|date|date_format:Y-m",
                "day_off" => "required|array",
                "day_off.*.driver_code" => "required",
                "day_off.*.date_off" => "required|date|date_format:Y-m-d|after_or_equal:" . Carbon::parse($request['date'])->startOfMonth()->toDateString() . "|before_or_equal:" . Carbon::parse($request['date'])->endOfMonth()->toDateString(),
                "day_off.*.type" => ["nullable", Rule::in($arrayConfigType)],
                "day_off.*.has_codes" => 'nullable'
            ];
        }
         if(Route::getCurrentRoute()->getActionMethod() == 'index'){
             return  [
                 "date" => "required|date|date_format:Y-m",
             ];
         }
     }

    public function messages()
    {
        return [
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'in' => trans('validation.in'),
        ];
    }
}
