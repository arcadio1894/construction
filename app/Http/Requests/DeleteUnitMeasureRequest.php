<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUnitMeasureRequest extends FormRequest
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
            'unitMeasure_id' => 'required|exists:unit_measures,id',
        ];
    }

    public function messages()
    {
        return [
            'unitMeasure_id.required' => 'El :attribute es obligatorio.',
            'unitMeasure_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'unitMeasure_id' => 'id de la unidad de medida'
        ];
    }
}
