<?php

use Illuminate\Database\Seeder;
use \App\PorcentageQuote;

class PorcentagesQoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PorcentageQuote::create([
            'name' => 'utility',
            'value' => 20.00,
        ]);
        PorcentageQuote::create([
            'name' => 'rent',
            'value' => 2.00,
        ]);
        PorcentageQuote::create([
            'name' => 'letter',
            'value' => 3.70,
        ]);
        PorcentageQuote::create([
            'name' => 'igv',
            'value' => 18.00,
        ]);
    }
}
