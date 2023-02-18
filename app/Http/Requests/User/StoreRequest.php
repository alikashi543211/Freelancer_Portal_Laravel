<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'email' => 'required|email:dns,rfc|unique:users,email',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'password' => 'required|confirmed:password_confirmation',
            'role_id' => 'required|exists:roles,id'
        ];
    }
}
