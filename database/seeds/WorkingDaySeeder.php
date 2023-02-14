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
            'description' => 'H. REGULAR',
            'time_start' => '08:00:00.000',
            'time_fin' => '17:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'H. ADMINISTRATIVO',
            'time_start' => '08:00:00.000',
            'time_fin' => '18:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'H. SABADO',
            'time_start' => '08:00:00.000',
            'time_fin' => '12:00:00.000',
            'enable' => true,
        ]);
        WorkingDay::create([
            'description' => 'H. NOCTURNO',
            'time_start' => '21:00:00.000',
            'time_fin' => '06:00:00.000',
            'enable' => true,
        ]);
    }
}
