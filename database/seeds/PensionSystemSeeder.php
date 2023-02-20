<?php

use Illuminate\Database\Seeder;
use App\PensionSystem;

class PensionSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PensionSystem::create([
            'description' => 'INTEGRA',
            'percentage' => 11.84,
            'enable' => 1
        ]);
        PensionSystem::create([
            'description' => 'PRIMA',
            'percentage' => 12.02,
            'enable' => 1
        ]);
        PensionSystem::create([
            'description' => 'PROFUTURO',
            'percentage' => 12.12,
            'enable' => 1
        ]);
        PensionSystem::create([
            'description' => 'HABITAT',
            'percentage' => 12.07,
            'enable' => 1
        ]);
        PensionSystem::create([
            'description' => 'ONP',
            'percentage' => 13,
            'enable' => 1
        ]);
    }
}
