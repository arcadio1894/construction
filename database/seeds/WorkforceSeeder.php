<?php

use Illuminate\Database\Seeder;
use App\Workforce;
use App\UnitMeasure;

class WorkforceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Workforce::create([
            'description' => 'HERRAMIENTAS',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'SEGURO',
            'unit_measure_id' => 15,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'EPP',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'FLETE',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'TRANSPORTE PARA RECOGER MATERIALES',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'TRANSPORTE PARA ENVÍO A PLANTA',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
    }
}
