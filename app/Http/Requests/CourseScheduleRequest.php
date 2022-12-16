<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CourseScheduleRequest extends FormRequest
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
                    return  [
                        'view_date' => 'sometimes|date|date_format:Y-m',
                        'field' => 'nullable|in:course_code,flag',
                        'sortby' => 'nullable|in:asc,desc'
                    ];
                default:
                    return [];
          }
    }

    public function messages()
    {
        return [
            'required' => ':attribute not null'
        ];
    }
}
