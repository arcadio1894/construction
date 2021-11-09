<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteTypeScrapRequest extends FormRequest
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
            'typeScrap_id' => 'required|exists:typescraps,id',
        ];
    }

    public function messages()
    {
        return [
            'typeScrap_id.required' => 'El :attribute es obligatorio.',
            'typeScrap_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'typeScrap_id' => 'id del tipo de retacería'
        ];
    }
}
