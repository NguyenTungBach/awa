<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;


class CashOutStatisticalRequest extends FormRequest
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
        switch (Route::getCurrentRoute()->getActionMethod()) {
            case 'index':
                return $this->getCustomRuleIndex();
            case 'show':
                return $this->getCustomRuleShow();
            case 'export':
                return $this->getCustomRuleIndex();
            default:
                return [];
        }
    }

    public function getCustomRuleIndex(){
        $rules = [
            'order_by' => [
                'sometimes',
                Rule::in(['id', 'driver_code', 'driver_name', 'balance_previous_month', 'payable_this_month', 'total_payable', 'total_cash_out_current', 'balance_current'])
            ],
            'sort_by' =>  [
                'sometimes',
                Rule::in(SORT_BY)
            ],
            'month_line' =>  [
                'sometimes',
                'date_format:Y-m',
            ]
        ];

        return $rules;
    }

    public function getCustomRuleShow(){
        $rules = [
            'month_line' =>  [
                'sometimes',
                'date_format:Y-m',
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // order_by
            'payment_method.in' => __('validation.in', ['attribute' => 'までに注文する']),
            // sort_by
            'payment_method.in' => __('validation.in', ['attribute' => '並び替え']),
            // month_line
            'month_line.date_format' => __('validation.date_format', ['attribute' => '並び替え', 'format' => 'Y-m']),
        ];
    }
}
