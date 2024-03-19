<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishContractStoreRequest extends FormRequest
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
            'worker_id' => 'required|exists:workers,id',
            'contract_id' => 'required|exists:contracts,id',
            'date_finish' => 'required|date_format:d/m/Y',
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'contract_id.required' => 'El :attribute es obligatorio.',
            'contract_id.exists' => 'El :attribute no existe en la base de datos.',
            'date_finish.required' => 'El :attribute es obligatorio.',
            'date_finish.date_format' => 'La :attribute no tiene el formato valido de fecha.',
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
            'contract_id' => 'contrato',
            'date_finish' => 'fecha',
        ];
    }
}
