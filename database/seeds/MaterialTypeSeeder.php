<?php

use Illuminate\Database\Seeder;
use App\MaterialType;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialType::create([
            'name' => 'ROSCADO',
            'description' => 'ROSCADO',
            'subcategory_id' => 1
        ]);
        MaterialType::create([
            'name' => 'SOLDABLE',
            'description' => 'SOLDABLE',
            'subcategory_id' => 1
        ]);
        MaterialType::create([
            'name' => 'OD',
            'description' => 'OD',
            'subcategory_id' => 1
        ]);
    }
}
