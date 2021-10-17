<?php

use Illuminate\Database\Seeder;
use App\Typescrap;

class TypescrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Typescrap::create([
            'name' => 'Planchas chicas',
            'length' => 1220,
            'width' => 2440,
        ]);

        Typescrap::create([
            'name' => 'Planchas grandes',
            'length' => 1500,
            'width' => 3000,
        ]);

        Typescrap::create([
            'name' => 'Tubos/Platinas/Barras/Ángulos',
            'length' => 6000,
        ]);
    }
}
