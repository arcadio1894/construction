<?php

use Illuminate\Database\Seeder;
use App\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create([
            'name' => 'Sistemas',
            'comment' => 'Área de sistemas'
        ]);

        Area::create([
            'name' => 'Producción',
            'comment' => 'Área de producctión'
        ]);
    }
}
