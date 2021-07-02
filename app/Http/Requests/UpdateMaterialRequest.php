<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'material_id' => 'required|exists:materials,id',
            'description' => 'required|string|max:255',
            'measure' => 'required|string|max:255',
            'unit_measure' => 'required|string|max:255',
            'stock_max' => 'required|numeric|min:0',
            'stock_min' => 'required|numeric|min:0',
            'stock_current' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|between:0,99999.99',
            'image' => 'image',
            'material_type' => 'required|exists:material_types,id',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'exampler' => 'required|exists:examplers,id',
            'serie' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'material_id.required' => 'El :attribute es obligatorio.',
            'material_id.exists' => 'El :attribute no existe en la vase de datos.',
            'description.required' => 'El :attribute es obligatorio.',
            'description.string' => 'El :attribute debe contener caracteres válidos',
            'description.max' => 'El :attribute es demasiado largo.',
            'measure.required' => 'El :attribute es obligatorio.',
            'measure.string' => 'El :attribute debe contener caracteres válidos.',
            'measure.max' => 'El :attribute es demasiado largo.',
            'unit_measure.required' => 'El :attribute es obligatorio.',
            'unit_measure.string' => 'El :attribute debe contener caracteres válidos.',
            'unit_measure.max' => 'El :attribute es demasiado largo.',
            'stock_max.required' => 'El :attribute es obligatorio.',
            'stock_max.numeric' => 'El :attribute debe ser un número.',
            'stock_max.min' => 'El :attribute debe ser mayor a 0.',
            'stock_min.required' => 'El :attribute es obligatorio.',
            'stock_min.numeric' => 'El :attribute debe ser un número.',
            'stock_current.min' => 'El :attribute debe ser mayor a 0.',
            'stock_current.required' => 'El :attribute es obligatorio.',
            'stock_current.numeric' => 'El :attribute debe ser un número.',
            'stock_min.min' => 'El :attribute debe ser mayor a 0.',
            'unit_price.required' => 'El :attribute es obligatorio.',
            'unit_price.numeric' => 'El :attribute debe ser un número.',
            'unit_price.between' => 'El :attribute esta fuera del rango numérico.',
            'image.image' => 'La :attribute debe ser un formato de imagen correcto',
            'material_type.exists' => 'El :attribute no existe en la base de datos.',
            'material_type.required' => 'El :attribute es obligatorio.',
            'category.exists' => 'El :attribute no existe en la base de datos.',
            'category.required' => 'La :attribute es obligatoria.',
            'brand.exists' => 'El :attribute no existe en la base de datos.',
            'brand.required' => 'La :attribute es obligatoria.',
            'exampler.exists' => 'El :attribute no existe en la base de datos.',
            'exampler.required' => 'La :attribute es obligatoria.',
            'serie.string' => 'El :attribute debe contener caracteres válidos.',
            'serie.max' => 'La :attribute es demasiado largo.',
            'serie.nullable' => 'La :attribute puede ser nula.'
        ];
    }

    public function attributes()
    {
        return [
            'material_id' => 'producto',
            'description' => 'descripción',
            'measure' => 'medida',
            'unit_measure' => 'unidad de medida',
            'stock_max' => 'stock máximo',
            'stock_min' => 'stock mínimo',
            'stock_current' => 'stock actual',
            'unit_price' => 'precio unitario',
            'image' => 'imagen',
            'material_type' => 'tipo de material',
            'category' => 'categoría',
            'brand' => 'marca',
            'exampler' => 'modelo',
            'serie' => 'serie'
        ];
    }
}
