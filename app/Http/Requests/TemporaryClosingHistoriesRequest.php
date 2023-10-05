<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use App\Rules\CheckTempExistsFinalRule;

class TemporaryClosingHistoriesRequest extends FormRequest
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
            case 'checkTemporary':
                return $this->getCustomRuleCheckTemporary();
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $rules = [
            'month_year' => [
                'required',
                'date_format:Y-m',
                new CheckTempExistsFinalRule($this->get('month_year')),
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

    public function getCustomRuleCheckTemporary(){
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
        ];
    }
}
