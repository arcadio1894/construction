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
            'name' => 'CONSUMIBLES',
            'description' => 'CONSUMIBLES',
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
            'name' => 'ESTRUCTURAS',
            'description' => 'ESTRUCTURAS',
        ]);
        Category::create([
            'name' => 'HERRAMIENTAS',
            'description' => 'HERRAMIENTAS NUEVAS',
        ]);
        Category::create([
            'name' => 'PERNERÍA',
            'description' => 'PERNOS EN GENERAL',
        ]);
    }
}
