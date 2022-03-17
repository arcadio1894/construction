<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsMaterialsInOrderForAlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'showMaterials_orderExecutionAlmacen',
            'description' => 'Ver Módulo de materiales en órdenes para almacén'
        ]);
        Permission::create([
            'name' => 'listOrders_orderExecutionAlmacen',
            'description' => 'Listar órdenes de ejecución para almacén'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'showMaterials_orderExecutionAlmacen',
            'listOrders_orderExecutionAlmacen',
        ]);

        $roleAl = Role::findByName('almacen');

        $roleAl->givePermissionTo([
            'showMaterials_orderExecutionAlmacen',
            'listOrders_orderExecutionAlmacen',
        ]);
    }
}
