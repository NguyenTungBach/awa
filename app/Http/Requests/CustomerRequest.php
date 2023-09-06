<?php
/**
 * Created by VeHo.
 * Year: 2023-07-20
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use App\Rules\PhoneNumber;
use App\Rules\PostCode;
use App\Models\Customer;

class CustomerRequest extends FormRequest
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
            case 'update':
                return $this->getCustomRuleUpdate();
            case 'index':
                return $this->getCustomRuleIndex();
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $rules = [
            'customer_code' => [
                'required',
                'min:4',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:customers,customer_code,NULL,id,deleted_at,NULL',
            ],
            'tax' => 'required|in:1,2',
            'customer_name' => 'required|string|max:20',
            'closing_date' => [
                'required',
                Rule::in(config('customers.closing_date'))
            ],
            'person_charge' => 'required|string|max:20',
            'post_code' => ['required', new PostCode(__('customers.post_code'))],
            'address' => 'required|string|max:100',
            'phone' => [
                'required',
                new PhoneNumber(__('customers.phone'))
            ],
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function getCustomRuleUpdate(){
        $customer = Customer::find(request()->route('customer'));

        $rules = [
            'customer_code' => [
                'sometimes',
                'required',
                'min:4',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('customers')->ignore($customer->id),
            ],
            'tax' => 'required|in:1,2',
            'customer_name' => 'sometimes|required|string|max:20',
            'closing_date' => [
                'sometimes',
                'required',
                Rule::in(config('customers.closing_date'))
            ],
            'person_charge' => 'sometimes|required|string|max:20',
            'post_code' => ['sometimes', 'required', new PostCode(__('customers.post_code'))],
            'address' => 'sometimes|required|string|max:100',
            'phone' => [
                'sometimes',
                'required',
                new PhoneNumber(__('customers.phone'))
            ],
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function getCustomRuleIndex(){
        $rules = [
            'order_by' => [
                'sometimes',
                Rule::in(['customer_code', 'customer_name', 'closing_date'])
            ],
            'sort_by' => [
                'sometimes',
                Rule::in(SORT_BY)
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // customer_code
            'customer_code.required' => __('validation.required', ['attribute' => __('customers.customer_code')]),
            'customer_code.min' => __('validation.between.numeric', ['attribute' => __('customers.customer_code'), 'min' => 4, 'max' => 15]),
            'customer_code.max' => __('validation.between.numeric', ['attribute' => __('customers.customer_code'), 'min' => 4, 'max' => 15]),
            'customer_code.regex' => __('validation.regex', ['attribute' => __('customers.customer_code')]),
            'customer_code.unique' => __('validation.unique', ['attribute' => __('customers.customer_code')]),
            // customer_name
            'customer_name.required' => __('validation.required', ['attribute' => __('customers.customer_name')]),
            'customer_name.string' => __('validation.string', ['attribute' => __('customers.customer_name')]),
            'customer_name.max' => __('validation.max.string', ['attribute' => __('customers.customer_name'), 'max' => 20]),
            // closing_date
            'closing_date.required' => __('validation.required', ['attribute' => __('customers.closing_date')]),
            'closing_date.in' => __('validation.in', ['attribute' => __('customers.closing_date')]),
            // person_charge
            'person_charge.required' => __('validation.required', ['attribute' => __('customers.person_charge')]),
            'person_charge.string' => __('validation.string', ['attribute' => __('customers.person_charge')]),
            'person_charge.max' => __('validation.max.string', ['attribute' => __('customers.person_charge'), 'max' => 20]),
            // post_code
            'post_code.required' => __('validation.required', ['attribute' => __('customers.post_code')]),
            // address
            'address.required' => __('validation.required', ['attribute' => __('customers.address')]),
            'address.string' => __('validation.string', ['attribute' => __('customers.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('customers.address'), 'max' => 100]),
            // phone
            'phone.required' => __('validation.required', ['attribute' => __('customers.phone')]),
            // 'phone.regex' => __('validation.custom.phone_number.regex', ['attribute' => __('customers.phone')]),
            // note
            'note.string' => __('validation.string', ['attribute' => __('customers.note')]),
            'note.max' => __('validation.max.string', ['attribute' => __('customers.note'), 'max' => 1000]),
        ];
    }
}
