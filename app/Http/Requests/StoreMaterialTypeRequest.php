<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'length' => 'string|max:255',
            'width' => 'string|max:255',
            'weight' => 'string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            
            
            'length.string' => 'El :attribute debe contener caracteres válidos.',            
            'length.max' => 'El :attribute es demasiado largo.',
            

            'width.string' => 'El :attribute debe contener caracteres válidos.',            
            'width.max' => 'El :attribute es demasiado largo.',

            'weight.string' => 'El :attribute debe contener caracteres válidos.',            
            'weight.max' => 'El :attribute es demasiado largo.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del tipo de material',
            'length' => 'largo del tipo de material',
            'width' => 'ancho del tipo de material',
            'weight' => 'peso del tipo de material',
            
        ];
    }
}
