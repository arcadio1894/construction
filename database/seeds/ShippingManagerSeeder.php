<?php

use Illuminate\Database\Seeder;
use \App\ShippingManager;

class ShippingManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingManager::create(['user_id' => 5]);
        ShippingManager::create(['user_id' => 6]);
        ShippingManager::create(['user_id' => 3]);
        ShippingManager::create(['user_id' => 2]);
        ShippingManager::create(['user_id' => 64]);
        ShippingManager::create(['user_id' => 34]);
        ShippingManager::create(['user_id' => 27]);
        ShippingManager::create(['user_id' => 61]);
        ShippingManager::create(['user_id' => 4]);
        ShippingManager::create(['user_id' => 46]);
    }
}
