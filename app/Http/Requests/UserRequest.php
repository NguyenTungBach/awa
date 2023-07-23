<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            case 'update':
                return $this->getCustomRuleUpdate();
            case 'updateone':
                return $this->getCustomRuleUpdateOne();
            case 'index':
                return $this->getCustomRuleIndex();
            case 'changePassword':
                return $this->getCustomRuleChangePassword();
            default:
                return [];
        }
    }

    public function getCustomRuleStore()
    {
        $rules = [
            'user_code' => [
                'required',
                'min:4',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:users,user_code',
            ],
            'user_name' => 'required|string|max:20',
            'password' => 'required|string|min:8|max:16|regex:/^[a-zA-Z0-9]+$/',
            'role' => [
                'required',
                Rule::in(config('users.role'))
            ],
            'status' => 'nullable',
        ];

        return $rules;
    }

    public function getCustomRuleUpdateOne()
    {
        return [
            'user_name' => 'required|string',
            'user_code' => 'required|unique:users,user_code,null,user_code,deleted_at,NULL',
            'roles*' => 'required|string',
            'department_id' => 'required|number',
            'password' => 'required|string'
        ];
    }

    public function getCustomRuleUpdate()
    {
        $user = User::find(request()->route('user'));

        $rules = [
            'user_code' => [
                'sometimes',
                'required',
                'min:4',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'user_name' => 'sometimes|required|string|max:20',
            'password' => 'sometimes|required|string|min:8|max:16|regex:/^[a-zA-Z0-9]+$/',
            'role' => [
                'sometimes',
                'required',
                Rule::in(config('users.role'))
            ],
            'status' => 'nullable',
        ];

        return $rules;
    }

    public function getCustomRuleIndex()
    {
        $rules = [
            'order_by' => [
                'nullable',
                Rule::in(['user_code', 'user_name', 'role'])
            ],
            'sort' => [
                'nullable',
                Rule::in(SORT_BY)
            ]
        ];

        return $rules;
    }

    public function getCustomRuleChangePassword()
    {
        return [
            // 'password' => 'required|min:8'
        ];
    }


    public function messages()
    {
        return [
            // user_code
            'user_code.required' => __('validation.required', ['attribute' => __('users.user_code')]),
            'user_code.min' => __('validation.between.numeric', ['attribute' => __('users.user_code'), 'min' => 4, 'max' => 15]),
            'user_code.max' => __('validation.between.numeric', ['attribute' => __('users.user_code'), 'min' => 4, 'max' => 15]),
            'user_code.regex' => __('validation.regex', ['attribute' => __('users.user_code')]),
            'user_code.unique' => __('validation.unique', ['attribute' => __('users.user_code')]),
            // user_name
            'user_name.required' => __('validation.required', ['attribute' => __('users.user_name')]),
            'user_name.string' => __('validation.string', ['attribute' => __('users.user_name')]),
            'user_name.max' => __('validation.max.string', ['attribute' => __('users.user_name'), 'max' => 20]),
            // password
            'password.required' => __('validation.required', ['attribute' => __('users.password')]),
            'password.string' => __('validation.string', ['attribute' => __('users.password')]),
            'password.min' => __('validation.between.numeric', ['attribute' => __('users.password'), 'min' => 8, 'max' => 16]),
            'password.max' => __('validation.between.numeric', ['attribute' => __('users.password'), 'min' => 8, 'max' => 16]),
            'password.regex' => __('validation.regex', ['attribute' => __('users.password')]),
            // role
            'role.required' => __('validation.required', ['attribute' => __('users.role')]),
            'role.in' => __('validation.in', ['attribute' => __('users.role')]),
        ];
    }
}
