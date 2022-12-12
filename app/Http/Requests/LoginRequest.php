<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            "user_code" => "required|max:4|alpha_dash", ["regex:r'^[a-zA-Z0-9]*$"],
            "password" => "required|min:8|max:16|alpha_dash", ["regex:r'^[a-zA-Z0-9]*$"],
        ];
    }

    /** message
     * @return string[]
     */
    public function messages()
    {
        return [
            'user_code.required' => trans('auth.login.required'),
            'password.required' => trans('auth.login.required'),
            'password.*' => trans('auth.failed'),
            'user_code.*' => trans('auth.failed')
        ];
    }
}
