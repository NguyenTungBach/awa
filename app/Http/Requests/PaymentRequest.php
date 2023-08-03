<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
                return $this->getCustomRuleIndex();
            default:
                return [];
        }
    }

    public function getCustomRuleIndex(){
        $rules = [
            'order_by' => [
                'sometimes',
                Rule::in(['drivers.id', 'drivers.driver_code', 'drivers.driver_name'])
            ],
            'sort_by' => [
                'sometimes',
                Rule::in(SORT_BY)
            ],
            'month_year' => [
                'sometimes',
                'date_format:Y-m'
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // order_by
            'order_by.in' => __('validation.in', ['attribute' => 'order_by']),
            // sort_by
            'sort_by.in' => __('validation.in', ['attribute' => 'sort_by']),
            // month_year
            'month_year.date_format' => __('validation.date_format', ['attribute' => 'month_year', 'format' => 'Y-m']),
        ];
    }
}
