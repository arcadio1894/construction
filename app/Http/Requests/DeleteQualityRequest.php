<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteQualityRequest extends FormRequest
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
            'quality_id' => 'required|exists:qualities,id',
        ];
    }

    public function messages()
    {
        return [
            'quality_id.required' => 'El :attribute es obligatorio.',
            'quality_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'quality_id' => 'id de la calidad de material'
        ];
    }
}
