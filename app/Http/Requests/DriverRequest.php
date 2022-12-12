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
                case 'update':
                    return [
                        "flag" => ['required',new DriverRule()],
                        "driver_name" => "required|string|max:20",
                        "grade" => ["required", 'regex:/^\d{1,10}$/'],
                        "start_date" => "required|date|date_format:Y-m-d",
                        "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                        "birth_day" => "required|date|date_format:Y-m-d",
                        "working_day" => ['required',Rule::in([1,2,3,4,5])],
                        "day_of_week" => ['nullable',new DriverRule()],
                        "note" => "max:1000",
                    ];
                case 'store':
                    return  [
                        "flag" => ['required',new DriverRule()],
                        "driver_code" => ["required", "min:1", "max:15", "unique:drivers,driver_code,null,driver_code,deleted_at,NULL", "regex:/^(([a-zA-Z]|\d)+-*)*([a-zA-Z]|\d)$/"],
                        "driver_name" => "required|string|max:20",
                        "grade" => ["required", 'regex:/^\d{1,10}$/'],
                        "start_date" => "required|date|date_format:Y-m-d",
                        "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                        "birth_day" => "required|date|date_format:Y-m-d",
                        "working_day" => ['required',Rule::in([1,2,3,4,5])],
                        "day_of_week" => ['nullable',new DriverRule()],
                        "note" => "max:1000",
                    ];
                default:
                    return [];
          }
    }

    public function messages()
    {
        return [
            'flag.required' => '社員区分を選択してください。',
            'driver_code.max' => 'Crew番号は半角数字15桁で入力してください。',
            'driver_name.max' => 'Crew名は20文字以下で入力してください。',
            'driver_code.unique' => 'このCrew番号は既に登録されています。',
            'start_date.*' => '入社日の形式が正しくありません。',
            'end_date.*' => '退職日の形式が正しくありません。',
            'birth_day.*' => '生年月日の形式が正しくありません。',
            'note.*' => 'メモは1000文字以内を入力してください。',
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'regex' => trans('validation.regex'),
            'numeric' => trans('validation.numeric'),
            'min' => trans('validation.min'),
            'max' => trans('validation.max'),
            'exists' => trans('validation.exists'),
            'in' => trans('validation.in'),
            'in_array' => trans('validation.in_array'),
            'grade.required' => '入力されていない項目があります。',
            'grade.regex' => '等級は半角英数字10桁以内で入力してください。'
        ];
    }
}
