<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'area_id' => 'required|exists:areas,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'shelf_id' => 'required|exists:shelves,id',
            'level_id' => 'required|exists:levels,id',
            'container_id' => 'required|exists:containers,id',
            'position_id' => 'required|exists:positions,id'
        ];
    }

    public function messages()
    {
        return [
            'area_id.required' => 'El :attribute es obligatorio.',
            'area_id.exists' => 'El :attribute no existe en la base de datos.',
            'warehouse_id.required' => 'El :attribute es obligatorio.',
            'warehouse_id.exists' => 'El :attribute no existe en la base de datos.',
            'shelf_id.required' => 'El :attribute es obligatorio.',
            'shelf_id.exists' => 'El :attribute no existe en la base de datos.',
            'level_id.required' => 'La :attribute es obligatorio.',
            'level_id.exists' => 'La :attribute no existe en la base de datos.',
            'container_id.required' => 'La :attribute es obligatorio.',
            'container_id.exists' => 'La :attribute no existe en la base de datos.',
            'position_id.required' => 'La :attribute es obligatorio.',
            'position_id.exists' => 'La :attribute no existe en la base de datos.'

        ];
    }

    public function attributes()
    {
        return [
            'area_id' => 'área',
            'warehouse_id' => 'almacén',
            'shelf_id' => 'estante',
            'level_id' => 'fila',
            'container_id' => 'columna',
            'position_id' => 'posición',
        ];
    }
}
