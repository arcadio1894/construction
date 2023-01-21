<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSimpleOutputRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'request_date' => 'required|date_format:d/m/Y',
            'requesting_user' => 'required',
            'responsible_user' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'request_date.required' => 'La :attribute es obligatorio.',
            'request_date.date_format' => 'La :attribute debe ser una fecha válida.',
            'requesting_user.required' => 'El :attribute es obligatorio.',
            'responsible_user.required' => 'El :attribute es obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'request_date' => 'fecha de solicitud',
            'requesting_user' => 'usuario solicitante',
            'responsible_user' => 'usuario responsable'
        ];
    }
}
