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
        $role = Role::findByName('admin');

        $permissions = [
            'enable_sales' => 'Habilitar Mod. Ventas',
            'enable_defaultEquipment' => 'Habilitar Catálogo de equipos',
            'listCategory_defaultEquipment' => 'Listar Categorías de Catálogo',
            'createCategory_defaultEquipment' => 'Crear Categorías de Catálogo',
            'editCategory_defaultEquipment'=> 'Editar Categorías de Catálogo',
            'destroyCategory_defaultEquipment'=> 'Eliminar Categorías de Catálogo',
            'restoreCategory_defaultEquipment'=> 'Restaurar Categorías de Catálogo',
            'eliminatedCategory_defaultEquipment'=> 'Listar Categorías eliminadas de Catálogo',
        ];
        foreach ($permissions as $permissionName => $description) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['description' => $description, 'guard_name' => 'web']
            );

            $role->givePermissionTo($permission);
        }
    }
}
