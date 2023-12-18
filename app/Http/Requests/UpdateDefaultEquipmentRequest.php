<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDefaultEquipmentRequest extends FormRequest
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
            'nameequipment' => 'required|string',
            'largeequipment' => 'nullable|numeric',
            'widthequipment' => 'nullable|numeric',
            'highequipment' => 'nullable|numeric',
            'categoryequipmentid' => 'required|numeric',
            'detailequipment' => 'required|string',
            'utility' => 'nullable|numeric|min:0',
            'letter' => 'nullable|numeric|min:0',
            'taxes' => 'nullable|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'nameequipment.required' => 'El :attribute del equipo es requerido',
            'nameequipment.string' => 'El :attribute debe contener caracteres válidos',
            'largeequipment.numeric' => 'El :attribute debe ser un valor numérico',
            'widthequipment.numeric' => 'El :attribute debe ser un valor numérico',
            'highequipment.numeric' => 'El :attribute debe ser un valor numérico',
            'categoryequipmentid.required' => 'El :attribute del equipo es requerido',
            'categoryequipmentid.numeric' => 'La :attribute debe ser un valor numérico',
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
            'nameequipment' => 'nombre del equipo',
            'largeequipment' => 'largo',
            'widthequipment' => 'ancho',
            'highequipment' => 'alto',
            'categoryequipmentid' => 'categoría del equipo',
            'detail' => 'detalles del equipo',
            'utility' => 'utilidad',
            'letter' => 'letra',
            'taxes' => 'renta'
        ];
    }
}
