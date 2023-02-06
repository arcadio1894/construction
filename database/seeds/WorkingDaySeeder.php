<?php

use Illuminate\Database\Seeder;
use \App\WorkingDay;

class WorkingDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkingDay::create([
            'description' => 'HORARIO LUN-JUE',
            'time_start' => '08:00:00.000',
            'time_fin' => '18:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'HORARIO VIERNES',
            'time_start' => '08:00:00.000',
            'time_fin' => '17:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'HORARIO SABADO',
            'time_start' => '08:00:00.000',
            'time_fin' => '12:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'HORARIO NOCTURNO',
            'time_start' => '21:00:00.000',
            'time_fin' => '06:00:00.000',
            'enable' => true,
        ]);
    }
}
