<?php

use Illuminate\Database\Seeder;
use App\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'Principal',
            'comment' => 'Almacén principal',
            'area_id' => 1
        ]);
        Warehouse::create([
            'name' => 'Principal',
            'comment' => 'Almacén principal',
            'area_id' => 2
        ]);
    }
}
