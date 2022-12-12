<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Requests;

use App\Rules\CourseRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
                    return  [
                        "flag" => ['nullable',Rule::in([Course::COURSE_FLAG_YES,Course::COURSE_FLAG_NO])],
                        "pot" => ['nullable',Rule::in([Course::COURSE_FLAG_YES,Course::COURSE_FLAG_NO])],
                        "course_name" => ["required","string","max:30",new CourseRule()],
                        "start_time" => "required|date_format:H:i",
                        "end_time" => ['required', 'regex:/^([012]?[0-9]|3[0-1]):(00|15|30|45)$/'],
                        "break_time" => ['required', 'regex:/^([012]?[0-9]).(00|25|50|75)$/'],
                        "start_date" => "required|date|date_format:Y-m-d",
                        "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                        "group" => "nullable|min:1|max:2|regex:/^[a-zA-Z]+$/",
                        "point" =>["required",'numeric'],
                        "note" => "max:1000",
                    ];
                case 'store':
                    return  [
                        "flag" => ['nullable',Rule::in([Course::COURSE_FLAG_YES,Course::COURSE_FLAG_NO])],
                        "pot" => ['nullable',Rule::in([Course::COURSE_FLAG_YES,Course::COURSE_FLAG_NO])],
                        "course_code" => ["required", "min:1", "max:15", "unique:courses,course_code,null,courses_code,deleted_at,NULL", "regex:/^(([a-zA-Z]|\d)+-*)*([a-zA-Z]|\d)$/"],
                        "course_name" => "required|string|max:30|unique:courses,course_name,null,course_name,deleted_at,NULL",
                        "start_time" => "required|date_format:H:i",
                        "end_time" => ['required', 'regex:/^([012]?[0-9]|3[0-1]):(00|15|30|45)$/'],
                        "break_time" => ['required', 'regex:/^([012]?[0-9]).(00|25|50|75)$/'],
                        "start_date" => "required|date|date_format:Y-m-d",
                        "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
                        "group" => "nullable|min:1|max:2|regex:/^[A-Z]+$/",
                        "point" =>["required", "numeric"],
                        "note" => "max:1000",
                    ];
                default:
                    return [];
          }
    }

    public function messages()
    {
        return [
            'course_code.unique' => 'このコースIDは既に存在します。',
            'course_code.*' =>  'コースIDは半角数字15桁で入力してください。',
            'note.*' => 'メモは1000文字以内を入力してください。',
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'time_format' => trans('validation.date_format'),
            'regex' => trans('validation.regex'),
            'numeric' => trans('validation.numeric'),
            'min' => trans('validation.min'),
            'course_name.max' => "コース名は30文字以内で入力してください。",
            'exists' => trans('validation.exists'),
            'in' => trans('validation.in'),
            'in_array' => trans('validation.in_array')
        ];
    }
}
