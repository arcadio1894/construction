<?php

use Illuminate\Database\Seeder;
use App\GratiPeriod;

class GratiPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GratiPeriod::create([
            'description' => 'GRATI_JUL2023',
            'month' => 7,
            'year' => 2023
        ]);

        GratiPeriod::create([
            'description' => 'GRATI_DIC2023',
            'month' => 12,
            'year' => 2023
        ]);

        GratiPeriod::create([
            'description' => 'GRATI_JUL2024',
            'month' => 7,
            'year' => 2024
        ]);

        GratiPeriod::create([
            'description' => 'GRATI_DIC2024',
            'month' => 12,
            'year' => 2024
        ]);

        GratiPeriod::create([
            'description' => 'GRATI_JUL2025',
            'month' => 7,
            'year' => 2025
        ]);

        GratiPeriod::create([
            'description' => 'GRATI_DIC2025',
            'month' => 12,
            'year' => 2025
        ]);
    }
}
