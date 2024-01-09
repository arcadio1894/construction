<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactNameRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'phone' => 'nullable|string|max:12',
            'email' => 'nullable|string|max:255|email|unique:contact_names,email',
            'area' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'customer_id.required' => 'La :attribute es obligatorio.',
            'customer_id.exists' => 'La :attribute no es una empresa registrada.',

            'phone.string' => 'El :attribute debe contener caracteres válidos.',
            'phone.max' => 'El :attribute debe contener máximo 12 caracteres.',

            'email.string' => 'El :attribute debe contener caracteres válidos.',
            'email.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'email.email' => 'El :attribute debe ser un email válido.',
            'email.unique' => 'El :attribute ya existe en la base de datos.',

            'area.string' => 'El :attribute debe contener caracteres válidos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre de contacto',
            'customer_id' => 'empresa',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'area' => 'area'
        ];
    }
}
