<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Driver;
use App\Rules\CheckPaymentDate;

class CashOutRequest extends FormRequest
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
            case 'store':
                return $this->getCustomRuleStore();
            case 'index':
                return $this->getCustomRuleIndex();
            case 'update':
                return $this->getCustomRuleUpdate();
            case 'export':
                return $this->getCustomRuleIndex();
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $rules = [
            'cash_out' => [
                'required',
                'numeric'
            ],
            'payment_method' => [
                'required',
                Rule::in(config('cash_outs.payment_method')),
            ],
            'payment_date' => [
                'required',
                'date_format:Y-m-d',
                new CheckPaymentDate(__('cash_outs.payment_date')),
            ],
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function getCustomRuleIndex(){
        $rules = [
            'filter_month' => [
                'sometimes',
                'required',
                'date_format:Y-m'
            ],
        ];

        return $rules;
    }

    public function getCustomRuleUpdate(){
        $rules = [
            'cash_out' => [
                'sometimes',
                'required',
                'numeric'
            ],
            'payment_method' => [
                'sometimes',
                'required',
                Rule::in(config('cash_outs.payment_method')),
            ],
            'payment_date' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                new CheckPaymentDate(__('cash_outs.payment_date')),
            ],
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // cash_out
            'cash_out.required' => __('validation.required', ['attribute' => __('cash_outs.cash_out')]),
            'cash_out.numeric' => __('validation.numeric', ['attribute' => __('cash_outs.cash_out')]),
            // payment_method
            'payment_method.required' => __('validation.required', ['attribute' => __('cash_outs.payment_method')]),
            'payment_method.in' => __('validation.in', ['attribute' => __('cash_outs.payment_method')]),
            // payment_date
            'payment_date.required' => __('validation.required', ['attribute' => __('cash_outs.payment_date')]),
            'payment_method.date_format' => __('validation.date_format', ['attribute' => __('cash_outs.payment_method'), 'format' => 'Y-m-d']),
            // note
            'note.string' => __('validation.string', ['attribute' => __('cash_outs.note')]),
            'note.max' => __('validation.max.string', ['attribute' => __('cash_outs.note'), 'max' => 1000]),
            // filter_month
            'filter_month.required' => __('validation.required', ['attribute' => 'filter_month']),
            'filter_month.date_format' => __('validation.date_format', ['attribute' => 'filter_month', 'format' => 'Y-m']),
        ];
    }
}
