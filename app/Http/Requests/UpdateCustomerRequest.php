<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'business_name' => 'required|string|max:255',
            'RUC' => 'required|string|max:255|unique:customers,RUC',
            'code' => 'required|string|min:3|max:10|unique:customers,code',
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

            'customer_id.required' => 'El :attribute es obligatorio.',
            'customer_id.exists' => 'El :attribute debe existir en la base de datos.',

            'business_name.required' => 'La :attribute es obligatoria.',
            'business_name.string' => 'La :attribute debe contener caracteres válidos.',
            'business_name.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'business_name.unique' => 'La :attribute ya existe en la base de datos.',

            'RUC.required' => 'El :attribute es obligatorio.',
            'RUC.string' => 'El :attribute debe contener caracteres válidos.',            
            'RUC.max' => 'El :attribute es demasiado largo.',
            'RUC.unique' => 'El :attribute ya existe en la base de datos.',

            'code.required' => 'El :attribute es obligatorio.',
            'code.string' => 'El :attribute debe contener caracteres válidos.',
            'code.min' => 'El :attribute debe contener mínimo 3 caracteres.',
            'code.max' => 'El :attribute debe contener máximo 10 caracteres.',
            'code.unique' => 'El :attribute ya existe en la base de datos.',

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
            'customer_id' => 'id del cliente',
            'business_name' => 'Razón Soacial del cliente',
            'RUC' => 'RUC del cliente',
            'code' => 'codigo del cliente',
            'contact_name' => 'nombre de contacto del cliente',
            'adress' => 'dirección del cliente',
            'phone' => 'teléfono del cliente',
            'location' => 'codUbicacioón del cliente',
            'email' => 'email del cliente'
        ];
    }
}
