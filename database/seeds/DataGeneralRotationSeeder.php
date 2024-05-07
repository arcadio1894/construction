<?php

use Illuminate\Database\Seeder;
use App\DataGeneral;

class DataGeneralRotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataGeneral::create([
            'name' => 'start_rotation_baja',
            'valueText' => 's_r_b',
            'valueNumber' => 0,
            'module' => 'material',
            'description' => 'Inicio porcentage rotacion baja'
        ]);
        DataGeneral::create([
            'name' => 'end_rotation_baja',
            'valueText' => 'e_r_b',
            'valueNumber' => 40,
            'module' => 'material',
            'description' => 'Fin porcentage rotacion baja'
        ]);
        DataGeneral::create([
            'name' => 'start_rotation_media',
            'valueText' => 's_r_m',
            'valueNumber' => 40,
            'module' => 'material',
            'description' => 'Inicio porcentage rotacion media'
        ]);
        DataGeneral::create([
            'name' => 'end_rotation_media',
            'valueText' => 'e_r_m',
            'valueNumber' => 70,
            'module' => 'material',
            'description' => 'Fin porcentage rotacion media'
        ]);
        DataGeneral::create([
            'name' => 'start_rotation_alta',
            'valueText' => 's_r_a',
            'valueNumber' => 70,
            'module' => 'material',
            'description' => 'Inicio porcentage rotacion alta'
        ]);
    }
}
