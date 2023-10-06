<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CashInStaticalRequest extends FormRequest
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
              case 'show':
                  return $this->getCustomRule();
              case 'index':
                  return $this->getCustomRule();
              case 'exportCashInStatical':
                  return $this->getCustomRule();
              case 'cashInStaticalTemp':
                  return $this->getCustomRule();
                default:
                    return [];
          }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [

            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return  [

            ];
        }
         if(Route::getCurrentRoute()->getActionMethod() == 'show'){
             return  [
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
             ];
         }
         if(Route::getCurrentRoute()->getActionMethod() == 'index'){
             return  [
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
                 "field" => "in:customer_code,customer_name,balance_previous_month,receivable_this_month,total_account_receivable,total_cash_in_of_current_month,total_cash_in_current",
                 "sortby" => "in:asc,desc"
             ];
         }
         if(Route::getCurrentRoute()->getActionMethod() == 'exportCashInStatical'){
             return  [
                 "month_year" => [
                     'required',
                     "date_format:Y-m",
                 ],
                 "field" => "in:customer_code,customer_name,balance_previous_month,receivable_this_month,total_account_receivable,total_cash_in_of_current_month,total_cash_in_current",
                 "sortby" => "in:asc,desc"
             ];
         }
         if(Route::getCurrentRoute()->getActionMethod() == 'cashInStaticalTemp'){
             return  [
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
