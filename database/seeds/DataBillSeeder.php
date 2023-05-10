<?php

use Illuminate\Database\Seeder;
use \App\Bill;

class DataBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bill::create([
            'description' => 'MOVILIDAD IDA',
        ]);
        Bill::create([
            'description' => 'MOVILIDAD VUELTA',
        ]);
        Bill::create([
            'description' => 'MOVILIDAD PLANTA',
        ]);
        Bill::create([
            'description' => 'TAXI REGRESO',
        ]);
        Bill::create([
            'description' => 'ALMUERZO',
        ]);
        Bill::create([
            'description' => 'CENA',
        ]);
        Bill::create([
            'description' => 'AGUA',
        ]);
        Bill::create([
            'description' => 'COPIAS / MASCARILLAS',
        ]);
    }
}
