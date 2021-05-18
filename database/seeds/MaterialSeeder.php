<?php

use Illuminate\Database\Seeder;
use App\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::create([
            'code' => 'P-00001',
            'description' => 'PLANCHA LISA INOX C-304',
            'measure' => '0.8*1.22*2.44',
            'unit_measure' => 'UND',
            'stock_max' => 10,
            'stock_min'=> 1,
            'stock_current' => 0,
            'priority' => 'Agotado',
            'unit_price' => 0,
            'image' => 'no_image.png',
            'material_type_id' => 3,
            'category_id' => 1
        ]);

        Material::create([
            'code' => 'P-00002',
            'description' => 'PLANCHA LISA INOX C-304',
            'measure' => '1.2*1.22*2.44',
            'unit_measure' => 'UND',
            'stock_max' => 10,
            'stock_min'=> 1,
            'stock_current' => 3.21,
            'priority' => 'Completo',
            'unit_price' => 0,
            'image' => 'no_image.png',
            'material_type_id' => 3,
            'category_id' => 1
        ]);
    }
}
