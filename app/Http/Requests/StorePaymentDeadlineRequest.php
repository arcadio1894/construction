<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentDeadlineRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'days' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'La :attribute es obligatoria.',
            'description.string' => 'La :attribute debe contener caracteres válidos.',
            'description.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'days.required' => 'La :attribute es obligatorio.',
            'days.numeric' => 'La :attribute debe ser un número.',
            'days.min' => 'La :attribute no puede ser menor a 0.',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'descripción',
            'days' => 'cantidad de días',
        ];
    }
}
