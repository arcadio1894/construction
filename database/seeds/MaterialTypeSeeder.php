<?php

use Illuminate\Database\Seeder;

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
            'name' => 'ejes',
            'length' => '5m',
            'width' => '5m',
            'weight' => '1k',
            
        ]);
        MaterialType::create([
            'name' => 'tubos',
            'length' => '6m',
            'width' => '6m',
            'weight' => '2k',
        ]);
        MaterialType::create([
            'name' => 'planchas',
            'length' => '7m',
            'width' => '7m',
            'weight' => '3k',
        ]);
    }
}
