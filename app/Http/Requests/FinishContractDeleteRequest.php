<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishContractDeleteRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'contract_id.required' => 'El :attribute es obligatorio.',
            'contract_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
            'contract_id' => 'contrato',
        ];
    }
}
