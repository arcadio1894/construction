<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionEnableMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_material',
            'description' => 'Habilitar/Deshabilitar materiales'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_material'
        ]);
    }
}
