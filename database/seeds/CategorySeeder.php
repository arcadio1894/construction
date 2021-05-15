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
            'name' => 'PLANCHA LISA INOX',
            'description' => '5m*1m',
        ]);
        Category::create([
            'name' => 'TUBERIA INOX',
            'description' => '5m*2m',
        ]);
        Category::create([
            'name' => 'TUBO CUADRADO INOX',
            'description' => '5m*2m',
        ]);
    }
}
