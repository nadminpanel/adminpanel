<?php

namespace NAdminPanel\AdminPanel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $id = $this->route()->parameter('role');

        if($this->method() == 'PUT' || $this->method() == 'PATCH') {

            $name = 'required|max:15|regex:"^[a-z0-9_\-]+$"|unique:roles,name,'.$id;

        } else {

            $name = 'required|max:15|regex:"^[a-z0-9_\-]+$"|unique:roles,name';

        }

        return [
            'name'  =>  $name,
            'display_name'  =>  'required|max:30'
        ];
    }
}
