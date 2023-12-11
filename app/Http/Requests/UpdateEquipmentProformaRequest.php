<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentProformaRequest extends FormRequest
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
            //'equipments' => 'required|array',
            'utility' => 'nullable|numeric|min:0',
            'letter' => 'nullable|numeric|min:0',
            'taxes' => 'nullable|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'detail.required' => 'El :attribute del equipo es requerido',
            'detail.string' => 'El :attribute debe contener caracteres válidos',
            'utility.numeric' => 'La :attribute debe ser un valor numérico',
            'utility.min' => 'La :attribute no puede ser negativa',
            'letter.numeric' => 'La :attribute debe ser un valor numérico',
            'letter.min' => 'La :attribute no puede ser negativa',
            'taxes.numeric' => 'La :attribute debe ser un valor numérico',
            'taxes.min' => 'La :attribute no puede ser negativa',
        ];
    }

    public function attributes()
    {
        return [
            'detail' => 'detalles del equipo',
            'utility' => 'utilidad',
            'letter' => 'letra',
            'taxes' => 'renta'
        ];
    }
}
