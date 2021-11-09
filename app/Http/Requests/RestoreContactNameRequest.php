<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestoreContactNameRequest extends FormRequest
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
            'contactName_id' => 'required|exists:contact_names,id',
        ];
    }

    public function messages()
    {
        return [
            'contactName_id.required' => 'El :attribute es obligatorio.',
            'contactName_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'contactName_id' => 'id del contacto'
        ];
    }
}
