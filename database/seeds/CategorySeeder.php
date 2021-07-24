<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'EPP',
            'description' => 'EQUIPO PROTECCIÓN  PERSONAL',
        ]);
        Category::create([
            'name' => 'EMBALAJES',
            'description' => 'EMBALAJES',
        ]);
        Category::create([
            'name' => 'OFICINA',
            'description' => 'UTILES DE OFICINA',
        ]);
        Category::create([
            'name' => 'ACCESORIOS',
            'description' => 'ACCESORIOS VARIOS',
        ]);
        Category::create([
            'name' => 'CONSUMIBLES',
            'description' => 'VARIOS',
        ]);
    }
}
