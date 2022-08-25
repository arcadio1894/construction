<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeletePorcentageQuoteRequest extends FormRequest
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

    public function rules()
    {
        return [
            'porcentage_id' => 'required|exists:porcentage_quotes,id',
        ];
    }

    public function messages()
    {
        return [
            'porcentage_id.required' => 'El :attribute es obligatorio.',
            'porcentage_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'porcentage_id' => 'id del porcentaje de cotización'
        ];
    }
}
