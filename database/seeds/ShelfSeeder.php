<?php

use Illuminate\Database\Seeder;
use App\Shelf;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shelf::create([
            'name' => 'General',
            'comment' => 'Estante general',
            'warehouse_id' => 1
        ]);
        Shelf::create([
            'name' => 'General',
            'comment' => 'Estante general',
            'warehouse_id' => 2
        ]);
    }
}
