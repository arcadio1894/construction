<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermitHourDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'permitHour_id' => 'required|exists:permits_hours,id',
        ];
    }

    public function messages()
    {
        return [
            'permitHour_id.required' => 'El :attribute es obligatorio.',
            'permitHour_id.exists' => 'El :attribute seleccionado no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'permitHour_id' => 'permiso por hora',
        ];
    }
}