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
            'ruc' => 'required|digits:11|string',
            'address' => 'string|max:255',
            'location' => 'string|max:255',
            
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

            'ruc.required' => 'El :attribute es obligatorio.',
            'ruc.string' => 'El :attribute debe contener caracteres válidos.',            
            'ruc.digits' => 'El :attribute es demasiado largo.',
            'ruc.numeric' => 'El :attribute debe ser numerico.',

            'address.string' => 'La :attribute debe contener caracteres válidos.',
            'address.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'location.string' => 'La :attribute debe contener caracteres válidos.',            
            'location.max' => 'La :attribute debe contener máximo 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'customer_id' => 'id del cliente',
            'business_name' => 'Razón Soacial del cliente',
            'ruc' => 'RUC del cliente',
            'address' => 'dirección del cliente',
            'location' => 'codUbicacioón del cliente',
        ];
    }
}
