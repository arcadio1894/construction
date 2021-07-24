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
            'description' => 'required|string|max:255|unique:materials,description,'.$this->get('material_id'),
            'measure' => 'required|string|max:255',
            'unit_measure' => 'required|exists:unit_measures,id',
            'typescrap' => 'nullable|exists:typescraps,id',
            'stock_max' => 'nullable|numeric|min:0',
            'stock_min' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|between:0,99999.99',
            'image' => 'image',
            'type' => 'nullable|exists:material_types,id',
            'subtype' => 'nullable|exists:subtypes,id',
            'category' => 'required|exists:categories,id',
            'subcategory' => 'nullable|exists:subcategories,id',
            'brand' => 'nullable|exists:brands,id',
            'exampler' => 'nullable|exists:examplers,id',
            'warrant' => 'nullable|exists:warrants,id',
            'quality' => 'nullable|exists:qualities,id',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'El :attribute es obligatorio.',
            'description.string' => 'El :attribute debe contener caracteres válidos',
            'description.max' => 'El :attribute es demasiado largo.',
            'description.unique' => 'El :attribute ya está registrado.',

            'measure.required' => 'El :attribute es obligatorio.',
            'measure.string' => 'El :attribute debe contener caracteres válidos.',
            'measure.max' => 'El :attribute es demasiado largo.',

            'unit_measure.required' => 'El :attribute es obligatorio.',
            'unit_measure.exists' => 'El :attribute no existe en la base de datos.',

            'typescrap.exists' => 'El :attribute no existe en la base de datos.',

            'stock_max.required' => 'El :attribute es obligatorio.',
            'stock_max.numeric' => 'El :attribute debe ser un número.',
            'stock_max.min' => 'El :attribute debe ser mayor a 0.',

            'stock_min.required' => 'El :attribute es obligatorio.',
            'stock_min.numeric' => 'El :attribute debe ser un número.',
            'stock_min.min' => 'El :attribute debe ser mayor a 0.',

            'unit_price.required' => 'El :attribute es obligatorio.',
            'unit_price.numeric' => 'El :attribute debe ser un número.',
            'unit_price.between' => 'El :attribute esta fuera del rango numérico.',

            'image.image' => 'La :attribute debe ser un formato de imagen correcto',

            'type.exists' => 'El :attribute no existe en la base de datos.',
            'type.required' => 'El :attribute es obligatorio.',

            'subtype.exists' => 'El :attribute no existe en la base de datos.',
            'subtype.required' => 'El :attribute es obligatorio.',

            'category.exists' => 'El :attribute no existe en la base de datos.',
            'category.required' => 'La :attribute es obligatoria.',

            'subcategory.exists' => 'El :attribute no existe en la base de datos.',
            'subcategory.required' => 'La :attribute es obligatoria.',

            'brand.exists' => 'El :attribute no existe en la base de datos.',
            'brand.required' => 'La :attribute es obligatoria.',

            'exampler.exists' => 'El :attribute no existe en la base de datos.',
            'exampler.required' => 'La :attribute es obligatoria.',

            'warrant.exists' => 'El :attribute no existe en la base de datos.',
            'warrant.required' => 'La :attribute es obligatoria.',

            'quality.exists' => 'El :attribute no existe en la base de datos.',
            'quality.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'descripción',
            'measure' => 'medida',
            'unit_measure' => 'unidad de medida',
            'stock_max' => 'stock máximo',
            'stock_min' => 'stock mínimo',
            'unit_price' => 'precio unitario',
            'image' => 'imagen',
            'type' => 'tipo de material',
            'category' => 'categoría',
            'brand' => 'marca',
            'exampler' => 'modelo',
            'typescrap' => 'retacería',
            'quality' => 'calidad',
            'warrant' => 'cédula',
            'subtype' => 'subtipo de material',
        ];
    }
}
