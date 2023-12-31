<?php
/**
 * Created by VeHo.
 * Year: 2023-07-25
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class FinalClosingHistoriesRequest extends FormRequest
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
                return $this->getCustomRuleStore();
            case 'index':
                return $this->getCustomRuleIndex();
            case 'checkFinalClosing':
                return $this->getCustomRuleCheckFinalClosing();
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $rules = [
            'month_year' => [
                'required',
                'date_format:Y-m',
                'unique:final_closing_histories,month_year,NULL,id,deleted_at,NULL'
            ],
        ];

        return $rules;
    }

    public function getCustomRuleIndex(){
        $rules = [
            'month_year' => [
                'sometimes',
                'date_format:Y-m'
            ],
        ];

        return $rules;
    }

    public function getCustomRuleCheckFinalClosing(){
        $rules = [
            'month_year' => [
                'required',
                'date_format:Y-m',
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // month_year
            'month_year.required' => __('validation.required', ['attribute' => 'month_year']),
            'month_year.date_format' => __('validation.date_format', ['attribute' => 'month_year', 'format' => 'Y-m']),
            // type
            'type.required' => __('validation.required', ['attribute' => 'type']),
            'type.in' => __('validation.in', ['attribute' => 'type']),
        ];
    }
}
