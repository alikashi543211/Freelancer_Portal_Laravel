<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'id' => 'required|exists:users,id',
            'email' => 'required|email:rfc,dns|unique:users,email,' . request('id') . ',id',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'role_id' => 'required|exists:roles,id'
        ];
    }
}
