<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeletePercentageWorkerRequest extends FormRequest
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
            'percentage_id' => 'required|exists:percentage_workers,id',
        ];
    }

    public function messages()
    {
        return [
            'percentage_id.required' => 'El :attribute es obligatorio.',
            'percentage_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'percentage_id' => 'id del porcentaje de recursos humanos'
        ];
    }
}
