<?php

use Illuminate\Database\Seeder;
use App\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::create([
            'name' => 'General',
            'comment' => 'Nivel general',
            'shelf_id' => 1
        ]);
    }
}
