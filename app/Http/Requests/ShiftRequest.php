<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Requests;

use App\Rules\ShiftRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
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
        $request = \Illuminate\Http\Request::all();
        $dt = Carbon::now()->toDateString();
        if (Route::getCurrentRoute()->getActionMethod() == 'update') {
            return [];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'index') {
            return [
                "start_date" => "required|date|date_format:Y-m-d",
                "end_date" => "required|date|date_format:Y-m-d|after_or_equal:start_date",
                "date" => "nullable|date|date_format:Y-m",
                'field' => 'nullable|in:driver_code,flag,course_code,group',
                'sortby' => 'nullable|in:asc,desc',
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'checkDataResult') {
            return [
                "date" => "required|date|date_format:Y-m",
                // 'type' => ["required", Rule::in(['week', 'month'])],
            ];
        }

        if (Route::getCurrentRoute()->getActionMethod() == 'updateCell') {
            return [
                "date" => "required|date|date_format:Y-m",
                'shift_list' => "required|array",
                "shift_list.*.date_edit" => "required|date|date_format:Y-m-d|after_or_equal:start_date",
                "shift_list.*.driver_code" => "required",
                "shift_list.*.shift_list_update.*.type" =>"required",
                "shift_list.*.shift_list_update.*.start_time" =>['required',new ShiftRule()],
                "shift_list.*.shift_list_update.*.end_time" =>['required','after_or_equal:start_time',new ShiftRule()],
                "shift_list.*.shift_list_update.*.break_time" =>['required',new ShiftRule()],
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'editAI') {
            $startDate = $dt;
            $endDate = Carbon::parse($dt)->endOfMonth()->toDateString();
            if ($request['date'] && strtotime($request['date']) && strtotime($request['date']) != strtotime($dt)) {
                $dt = $request['date'];
                $startDate = Carbon::parse($dt)->startOfMonth()->toDateString();
                $endDate = Carbon::parse($dt)->endOfMonth()->toDateString();
            }
            $rules = [
                "date" => "required|date|date_format:Y-m",
                'items' => "required|array",
                "items.*.day" => "required|date|date_format:Y-m-d|after_or_equal:$startDate|before_or_equal:$endDate",
                "items.*.course_code" => ["required", "min:1", "max:15", "regex:/^(([a-zA-Z]|\d)+-*)*([a-zA-Z]|\d)$/"],
                "items.*.driver" => ["nullable", "max:15", "regex:/^(([a-zA-Z]|\d)+-*)*([a-zA-Z]|\d)$/"],
            ];

            return $rules;
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'detailCell') {
            return [
                "driver_code" => "required",
                "date" => "required|date|date_format:Y-m-d",
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'getMessageAI') {
            return [];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'checkInfomationAI') {
            return [
                "type" => "nullable",
                "start_date" => "nullable|date|date_format:Y-m-d",
                "end_date" => "nullable|date|date_format:Y-m-d|after_or_equal:start_date",
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            $dt = '2022-09-01';
            $startDate = $dt;
            $endDate = Carbon::parse($dt)->endOfMonth()->toDateString();
            if ($request['date'] && strtotime($request['date']) && strtotime($request['date']) > strtotime($dt)) {
                $dt = $request['date'];
                $startDate = Carbon::parse($dt)->startOfMonth()->toDateString();
                $endDate = Carbon::parse($dt)->endOfMonth()->toDateString();
            }

            return [
                "date" => "required|date|date_format:Y-m|after_or_equal:" . date('Y-m', strtotime($dt)),
                "start_date" => "sometimes|date|date_format:Y-m-d|after_or_equal:$startDate|before:$endDate",
                // "end_date" => "required|date|date_format:Y-m-d|date_equals:$endDate",
            ];
        }
    }

    public function messages()
    {
        return [
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'regex' => trans('validation.regex'),
            'numeric' => trans('validation.numeric'),
            'min' => trans('validation.min'),
            'max' => trans('validation.max'),
            'exists' => trans('validation.exists'),
            'in' => trans('validation.in'),
            'in_array' => trans('validation.in_array')
        ];
    }
}
