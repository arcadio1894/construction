<?php

use Illuminate\Database\Seeder;
use App\Quality;

class QualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Quality::create([
            'name' => 'C-304',
            'description' => 'C-304'
        ]);
        Quality::create([
            'name' => 'C-316',
            'description' => 'C-316'
        ]);
    }
}
