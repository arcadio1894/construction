<?php

use Illuminate\Database\Seeder;
use App\Container;

class ContainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Container::create([
            'name' => 'General',
            'comment' => 'Contenedor general',
            'level_id' => 1
        ]);

        Container::create([
            'name' => 'General',
            'comment' => 'Contenedor general',
            'level_id' => 2
        ]);
    }
}
