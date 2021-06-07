<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(MaterialTypeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CustomerSeeder::class);

        $this->call(MaterialSeeder::class);

        $this->call(AreaSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ShelfSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(ContainerSeeder::class);
        $this->call(LocationSeeder::class);
    }
}
