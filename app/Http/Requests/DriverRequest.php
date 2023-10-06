<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Requests;

use App\Models\Driver;
use App\Models\User;
use App\Rules\DriverRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class DriverRequest extends FormRequest
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
              case 'index':
                  return [
                      "sortby" => "in:asc,desc"
                  ];
              case 'driver_for_course':
                  return [
                      "ship_date" => "required|date_format:Y-m-d"
                  ];
              case 'store':
                  return  [
                      "type" => ["required", "in:1,2,3,4"],
                      "driver_code" => ["required", "min:1", "max:20", "unique:drivers,driver_code,null,driver_code,deleted_at,NULL", "regex:/^[0-9]+$/"],
                      "driver_name" => ['required','string','max:20','regex:/^[a-zA-Z0-9\sぁ-んァ-ン一-龥]+$/'],
                      "car" => "required|max:20",
                      "start_date" => "nullable|date|date_format:Y-m-d",
                      "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                      "note" => "max:1000",
                  ];
                case 'update':
                    return [
                        "type" => ["required", "in:1,2,3,4"],
                        "driver_code" => ["min:1", "max:20", "unique:drivers,driver_code,null,driver_code,deleted_at,NULL", "regex:/^[0-9]+$/"],
                        "driver_name" => ['required','string','max:20','regex:/^[a-zA-Z0-9\sぁ-んァ-ン一-龥]+$/'],
                        "car" => "required|max:20",
                        "start_date" => "nullable|date|date_format:Y-m-d",
                        "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                        "note" => "max:1000",
                    ];
                default:
                    return [];
          }
    }

    public function messages()
    {
        return [
            'driver_code.max' => 'Crew番号は半角数字15桁で入力してください。',
            'driver_name.max' => 'Crew名は20文字以下で入力してください。',
            'driver_code.unique' => 'このCrew番号は既に登録されています。',
            'start_date.*' => '入社日の形式が正しくありません。',
            'end_date.*' => '退職日の形式が正しくありません。',
            'note.*' => 'メモは1000文字以内を入力してください。',
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'regex' => trans('validation.regex'),
            'numeric' => trans('validation.numeric'),
            'min' => trans('validation.min'),
            'max' => trans('validation.max'),
            'in' => trans('validation.in'),
            // index
            'sortby.in' => 'Please input asc or desc',
            // store
        ];
    }
}
