<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPasswordRequest extends FormRequest
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
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'La :attribute es obligatorio.',
            'current_password.string' => 'La :attribute debe contener caracteres válidos.',
            'current_password.min' => 'La :attribute debe contener mínimp 8 caracteres.',
            'new_password.required' => 'La :attribute es obligatorio.',
            'new_password.string' => 'La :attribute debe contener caracteres válidos.',
            'new_password.min' => 'La :attribute debe contener mínimo 8 caracteres.',
            'new_password.confirmed' => 'La :attribute no coincide con la contraseña repetida.',

        ];
    }

    public function attributes()
    {
        return [
            'current_password' => 'contraseña actual',
            'new_password' => 'nueva contraseña'
        ];
    }
}
