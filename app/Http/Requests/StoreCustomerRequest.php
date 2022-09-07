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
            //'ruc' => 'required|digits:11|string|unique:customers,RUC',
            'ruc' => 'required|string|unique:customers,RUC',
            'address' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255'
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
            //'ruc.digits' => 'El :attribute es demasiado largo.',
            'ruc.unique' => 'El :attribute ya existe en la base de datos.',
            //'ruc.numeric' => 'El :attribute debe ser numerico.',

            'address.string' => 'La :attribute debe contener caracteres válidos.',
            'address.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'location.string' => 'La :attribute debe contener caracteres válidos.',            
            'location.max' => 'La :attribute debe contener máximo 255 caracteres.',

        ];
    }

    public function attributes()
    {
        return [
            'business_name' => 'Razón Soacial',
            'RUC' => 'RUC del cliente',
            'address' => 'dirección del cliente',
            'location' => 'codUbicacioón del cliente',
        ];
    }
}
