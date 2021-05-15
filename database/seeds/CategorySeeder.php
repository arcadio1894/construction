<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialType::create([
            'name' => 'CATEGORIA 1',
            'description' => '5m*1m',
        ]);
        MaterialType::create([
            'name' => 'CATEGORIA 2',
            'description' => '5m*2m',
        ]);
        MaterialType::create([
            'name' => 'CATEGORIA 3',
            'description' => '5m*2m',
        ]);
    }
}
