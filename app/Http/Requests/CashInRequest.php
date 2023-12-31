<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CashInRequest extends FormRequest
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
                    return $this->getCustomRule();
                case 'store':
                    return $this->getCustomRule();
              case 'index':
                  return $this->getCustomRule();
                default:
                    return [];
          }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [
                "customer_id"=>["required","numeric"],
                "cash_in"=>["required","numeric"],
                "payment_method"=>["required","numeric"],
                "payment_date"=>["required","date_format:Y-m-d"],
                "note" => ["max:1000"],
            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return  [
                "customer_id"=>["required","numeric"],
                "cash_in"=>["required","numeric"],
                "payment_method"=>["required","numeric"],
                "payment_date"=>["required","date_format:Y-m-d"],
                "note" => ["max:1000"],
            ];
        }
         if(Route::getCurrentRoute()->getActionMethod() == 'index'){
             return  [
                 "customer_id"=>["required","numeric"],
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
             ];
         }
     }

    public function messages()
    {
        return [
            'required' => ':attribute not null'
        ];
    }
}
