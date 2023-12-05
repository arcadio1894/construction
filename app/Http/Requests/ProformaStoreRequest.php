<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProformaStoreRequest extends FormRequest
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
            'code_quote' => 'string',
            'code_description' => 'nullable|string',
            'delivery_time' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'code_description.string' => 'El :attribute debe contener caracteres válidos.',
            'code_quote.string' => 'El :attribute debe contener caracteres válidos.',
            'delivery_time.string' => 'El :attribute debe contener caracteres válidos.',
            'customer_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'code_description' => 'descripción',
            'code_quote' => 'código',
            'date_quote' => 'fecha',
            'date_validate' => 'fecha válida',
            'delivery_time' => 'tiempo de entrega',
            'customer_id' => 'cliente',
            'equipments' => 'equipos',
        ];
    }
}
