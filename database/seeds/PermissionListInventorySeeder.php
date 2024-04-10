<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionListInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_inventory',
            'description' => 'Ver listado de inventario'
        ]);
        Permission::create([
            'name' => 'save_inventory',
            'description' => 'Guardar listado inventario'
        ]);
        Permission::create([
            'name' => 'export_inventory',
            'description' => 'Exportar listado inventario'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_inventory',
            'save_inventory',
            'export_inventory'
        ]);
    }
}
