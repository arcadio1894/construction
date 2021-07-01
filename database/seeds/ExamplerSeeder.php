<?php

use Illuminate\Database\Seeder;
use App\Exampler;

class ExamplerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exampler::create([
            'name' => 'M 470',
            'comment' => '',
            'brand_id' => 1
        ]);
    }
}
