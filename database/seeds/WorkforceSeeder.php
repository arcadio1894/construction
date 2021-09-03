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
        $unit = UnitMeasure::create(['name' => 'DIAS', 'description' => 'DIAS']);
        Workforce::create([
            'description' => 'DÍAS DE TRABAJO',
            'unit_measure_id' => $unit->id,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'HERRAMIENTAS',
            'unit_measure_id' => 1,
            'unit_price' => 0
        ]);
        Workforce::create([
            'description' => 'SEGURO',
            'unit_measure_id' => $unit->id,
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
            'description' => 'SERVICIO DE TORNO',
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
