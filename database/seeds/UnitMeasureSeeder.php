<?php

use Illuminate\Database\Seeder;
use App\UnitMeasure;

class UnitMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitMeasure::create(['name' => 'UND', 'description' => 'UNIDAD']);
        UnitMeasure::create(['name' => 'PQTE', 'description' => 'PAQUETE']);
        UnitMeasure::create(['name' => 'GL', 'description' => 'GALON']);
        UnitMeasure::create(['name' => 'MT', 'description' => 'METRO']);
        UnitMeasure::create(['name' => 'JGO', 'description' => 'JUEGO']);
        UnitMeasure::create(['name' => 'PAR', 'description' => 'PAR']);
        UnitMeasure::create(['name' => 'KIT', 'description' => 'KIT']);
        UnitMeasure::create(['name' => 'ROL', 'description' => 'ROLLO']);
        UnitMeasure::create(['name' => 'BL', 'description' => 'BOLSA']);
        UnitMeasure::create(['name' => 'KG', 'description' => 'KILO GRAMO']);
        UnitMeasure::create(['name' => 'CTO', 'description' => 'CIENTO']);
        UnitMeasure::create(['name' => 'BLIS', 'description' => 'BLISTER']);
        UnitMeasure::create(['name' => 'CJA', 'description' => 'CAJA']);
    }
}
