<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.g
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.v
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_name' => 'required|string|max:255',
            'ruc' => 'required|digits:11|string|unique:customers,RUC',
            'contact_name' => 'string|max:255',
            'adress' => 'string|max:255',
            'phone' => 'string|min:6|max:15',
            'location' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email'
            
        ];
    }

    public function messages()
    {
        return [
            'business_name.required' => 'La :attribute es obligatoria.',
            'business_name.string' => 'La :attribute debe contener caracteres válidos.',
            'business_name.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'business_name.unique' => 'La :attribute ya existe en la base de datos.',

            'ruc.required' => 'El :attribute es obligatorio.',
            'ruc.string' => 'El :attribute debe contener caracteres válidos.',            
            'ruc.digits' => 'El :attribute es demasiado largo.',
            'ruc.unique' => 'El :attribute ya existe en la base de datos.',
            'ruc.numeric' => 'El :attribute debe ser numerico.',

            'contact_name.string' => 'El :attribute debe contener caracteres válidos.',
            'contact_name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            
            'adress.string' => 'La :attribute debe contener caracteres válidos.',            
            'adress.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'phone.string' => 'El :attribute debe contener caracteres válidos.',
            'phone.min' => 'El :attribute debe contener mínimo 6 caracteres.',
            'phone.max' => 'El :attribute debe contener máximo 15 caracteres.',
            
            'location.string' => 'La :attribute debe contener caracteres válidos.',            
            'location.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'email.required' => 'El :attribute es obligatorio.',
            'email.string' => 'El :attribute debe contener caracteres válidos.',
            'email.email' => 'El :attribute no tiene formato de email adecuado.',
            'email.max' => 'El :attribute es demasiado largo.',
            'email.unique' => 'El :attribute ya existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'business_name' => 'Razón Soacial',
            'RUC' => 'RUC del cliente',
            'contact_name' => 'nombre de contacto',
            'adress' => 'dirección del cliente',
            'phone' => 'teléfono del cliente',
            'location' => 'codUbicacioón del cliente',
            'email' => 'email del cliente'
        ];
    }
}
