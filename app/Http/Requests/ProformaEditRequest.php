<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProformaEditRequest extends FormRequest
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
            'proforma_id' => 'required|exists:proformas,id',
            'code_quote' => 'nullable|string',
            'code_description' => 'nullable|string',
            'delivery_time' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'proforma_id.required' => 'El :attribute es obligatoria.',
            'proforma_id.exists' => 'El :attribute no existe en la base de datos.',
            'code_description.string' => 'El :attribute debe contener caracteres válidos.',
            'code_quote.string' => 'El :attribute debe contener caracteres válidos.',
            'delivery_time.string' => 'El :attribute debe contener caracteres válidos.',
        ];
    }

    public function attributes()
    {
        return [
            'proforma_id' => 'id',
            'code_description' => 'descripción',
            'code_quote' => 'código',
            'date_quote' => 'fecha',
            'date_validate' => 'fecha válida',
            'delivery_time' => 'tiempo de entrega'
        ];
    }
}
