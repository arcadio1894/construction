<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_sales',
            'description' => 'Habilitar Mod. Ventas'
        ]);

        Permission::create([
            'name' => 'enable_defaultEquipment',
            'description' => 'Habilitar Catálogo de equipos'
        ]);
        Permission::create([
            'name' => 'listCategory_defaultEquipment',
            'description' => 'Listar Categorías de Catálogo'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_sales',
            'enable_defaultEquipment',
            'listCategory_defaultEquipment'
        ]);
    }
}
