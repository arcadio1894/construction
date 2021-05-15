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
            'name' => 'Ejes',
            'length' => 6000,
            'width' => null,
            'weight' => null,
            
        ]);
        MaterialType::create([
            'name' => 'Planchas chicas',
            'length' => 2440,
            'width' => 1220,
            'weight' => null,
        ]);
        MaterialType::create([
            'name' => 'Planchas grandes',
            'length' => 3000,
            'width' => 1500,
            'weight' => null,
        ]);
    }
}
