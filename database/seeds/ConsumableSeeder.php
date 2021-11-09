<?php

use Illuminate\Database\Seeder;
use App\Material;

class ConsumableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Material::create(
            ['code' => 'P-02000',
                'description' => '(*) DISCO DE CORTE',
                'measure' => '7"',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 1.5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02001',
                'description' => '(*) DISCO DE PULIR',
                'measure' => '7"',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 4,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02002',
                'description' => '(*) ARGÓN GASEOSO',
                'measure' => '',
                'unit_measure_id' => 14,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 10,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02003',
                'description' => '(*) TUNGSTENO',
                'measure' => '',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 7.5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02004',
                'description' => '(*) APORTE INOX',
                'measure' => '3/32',
                'unit_measure_id' => 10,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 15,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02005',
                'description' => '(*) APORTE INOX',
                'measure' => '1/16',
                'unit_measure_id' => 10,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 15,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02006',
                'description' => '(*) ACIDO SOLDINOX',
                'measure' => '',
                'unit_measure_id' => 10,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 35,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02007',
                'description' => '(*) RUEDA DE LIMPIEZA AMOLADORA',
                'measure' => '',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 18,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02008',
                'description' => '(*) ESPONJA',
                'measure' => '',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 0.4,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02009',
                'description' => '(*) TRAPO INDUSTRIAL',
                'measure' => '',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 0.1,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02010',
                'description' => '(*) RUEDA DE LIMPIEZA TALADRO',
                'measure' => '',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 6,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02011',
                'description' => 'LIJA MIL HOJA',
                'measure' => 'GR80',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02012',
                'description' => 'LIJA MIL HOJA',
                'measure' => 'GR40',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02013',
                'description' => 'LIJA MIL HOJA',
                'measure' => 'GR120',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );
        Material::create(
            ['code' => 'P-02014',
                'description' => 'LIJA MIL HOJA',
                'measure' => 'GR120',
                'unit_measure_id' => 1,
                'priority' => 'Aceptable',
                'category_id' => 2,
                'subcategory_id' => null,
                'material_type_id' => null,
                'subtype_id' => null,
                'warrant_id' => null,
                'quality_id' => null,
                'unit_price' => 5,
                'image' => 'no_image.png',
                'stock_current' => 100,
                'stock_max' => 1,
                'stock_min'=> 0,
                'typescrap_id' => null,
                'brand_id' => null,
                'exampler_id' => null
            ]
        );*/
    }
}
