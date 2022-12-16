<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ReportRequest extends FormRequest
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
          switch (Route::getCurrentRoute()->getActionMethod()){
                case 'index':
                    return [
                        'view_date' => 'required|date|date_format:Y-m',
                        'field' => 'nullable|in:driver_code,flag',
                        'sortby' => 'nullable|in:asc,desc',
                        'status_view' => 'nullable|in:fix,month'
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
