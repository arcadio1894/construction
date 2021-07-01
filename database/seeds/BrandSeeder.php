<?php

use Illuminate\Database\Seeder;
use App\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'name' => 'Aceros Arequipa',
            'comment' => ''
        ]);
        Brand::create([
            'name' => 'Mannesmann',
            'comment' => ''
        ]);
    }
}
