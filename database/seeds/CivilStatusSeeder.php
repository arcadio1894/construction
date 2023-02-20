<?php

use Illuminate\Database\Seeder;
use \App\CivilStatus;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CivilStatus::create([
            'description' => 'Soltero(a)',
            'enable' => 1
        ]);
        CivilStatus::create([
            'description' => 'Casado(a)',
            'enable' => 1
        ]);
        CivilStatus::create([
            'description' => 'Conviviente',
            'enable' => 1
        ]);
    }
}
