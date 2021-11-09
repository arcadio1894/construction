<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'image' => 'image'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos',
            'name.max' => 'El :attribute es demasiado largo.',
            'email.required' => 'El :attribute es obligatorio.',
            'email.string' => 'El :attribute debe contener caracteres válidos.',
            'email.email' => 'El :attribute no tiene formato de email adecuado.',
            'email.max' => 'El :attribute es demasiado largo.',
            'email.unique' => 'El :attribute ya existe en la base de datos.',
            'image.image' => 'El :attribute debe ser un formato de imagen correcto'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del usuario',
            'email' => 'correo electrónico'
        ];
    }
}
