<?php

namespace NAdminPanel\AdminPanel\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
        $id = $this->route()->parameter('user');

        if($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return [
                'name'                  => 'required|max:15',
                'email'                 => 'required|max:255|email|email_checker|unique:users,email,'.$id
            ];
        }

        return [
            'name'                  => 'required|max:15',
            'email'                 => 'required|max:255|email|email_checker|unique:users,email',
            'password'              => 'required|max:255|min:4|confirmed',
            'password_confirmation'  => 'required|max:255|min:4'
        ];
    }
}
