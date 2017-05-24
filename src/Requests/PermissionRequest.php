<?php

namespace NAdminPanel\AdminPanel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
        $id = $this->route()->parameter('permission');

        if($this->method() == 'PUT' || $this->method() == 'PATCH') {

            return [
                'name'  =>  'required|max:15|regex:"^[a-z0-9_\-]+$"|unique:permission_labels,name,'.$id
            ];

        }

        return [
            'name'  =>  'required|max:15|regex:"^[a-z0-9_\-]+$"|unique:permission_labels,name'
        ];
    }
}
