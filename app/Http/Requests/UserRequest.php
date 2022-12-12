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
            case 'updateone':
                return $this->getCustomRuleUpdateOne();
            case 'changePassword':
                return $this->getCustomRuleChangePassword();
            case 'store':
                return $this->getCustomRuleStore();
            case 'update':
                return $this->getCustomRuleUpdate();
            default:
                return [];
        }
    }

    public function getCustomRuleUpdateOne()
    {
        return [
            "user_name" => "required|string",
            "user_code" => "required|unique:users,user_code,null,user_code,deleted_at,NULL",
            "roles*" => "required|string",
            "department_id" => "required|number",
            "password" => "required|string"
        ];
    }

    public function getCustomRuleChangePassword()
    {
        return [
            // 'password' => "required|min:8"
        ];
    }

    public function getCustomRuleStore()
    {
        return [
            "user_code" => "required|min:4|max:4|unique:users,user_code,null,user_code,deleted_at,NULL|regex:/^[0-9]+$/",
            "user_name" => "required|string|max:20",
            "password" => "required|string|min:8|max:16|regex:/^[a-zA-Z0-9]+$/",
            "role" => ['required',Rule::in([User::USER_ROLE_ADMIN,User::USER_ROLE_DRIVER])],
        ];
    }

    public function getCustomRuleUpdate()
    {
        return [
            "user_name" => "required|string|max:20",
            "password" => "nullable|string|min:8|max:16|regex:/^[a-zA-Z0-9]+$/",
            "role" => ['required',Rule::in([User::USER_ROLE_ADMIN,User::USER_ROLE_DRIVER])],
        ];
    }

    public function messages()
    {
        return [
            'user_code.*' => 'ユーザーIDは半角数字4桁で入力してください。',
            'required' => trans('validation.required'),
            'date_format' => trans('validation.date_format'),
            'numeric' => trans('validation.numeric'),
            'min' => trans('validation.min'),
            'max' => trans('validation.max'),
            'user_name.max' => 'ユーザー名は20文字以下で入力してください。',
            'exists' => trans('validation.exists'),
            'in' => trans('validation.in'),
            'in_array' => trans('validation.in_array'),
            'password.regex' => "パスワードは半角英数字で入力してください。",
        ];
    }
}
