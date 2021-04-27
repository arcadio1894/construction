<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'permission_id' => 'required|exists:permissions,id',
            'name' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:5|max:255'
        ];
    }

    public function messages()
    {
        return [
            'permission_id.required' => 'El :attribute es obligatorio.',
            'permission_id.exists' => 'El :attribute debe existir en la base de datos.',
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.min' => 'El :attribute debe contener mínimo 5 caracteres.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'description.required' => 'La :attribute es obligatorio.',
            'description.string' => 'La :attribute debe contener caracteres válidos.',
            'description.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'description.max' => 'La :attribute debe contener máximo 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'permission_id' => 'id del permiso',
            'name' => 'nombre del permiso',
            'description' => 'descripción del permiso'
        ];
    }
}
