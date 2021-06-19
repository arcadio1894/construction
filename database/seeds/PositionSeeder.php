<?php

use Illuminate\Database\Seeder;
use App\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'name' => 'General',
            'comment' => 'Posicion general',
            'container_id' => 1
        ]);

        Position::create([
            'name' => 'General',
            'comment' => 'Posicion general',
            'container_id' => 2
        ]);
    }
}
