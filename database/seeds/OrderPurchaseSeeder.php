<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class OrderPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_orderPurchaseExpress',
            'description' => 'Listar Ordenes Compra Express'
        ]);
        Permission::create([
            'name' => 'create_orderPurchaseExpress',
            'description' => 'Crear Ordenes Compra Express'
        ]);
        Permission::create([
            'name' => 'update_orderPurchaseExpress',
            'description' => 'Modificar Ordenes Compra Express'
        ]);
        Permission::create([
            'name' => 'destroy_orderPurchaseExpress',
            'description' => 'Eliminar Ordenes Compra Express'
        ]);

        Permission::create([
            'name' => 'list_orderPurchaseNormal',
            'description' => 'Listar Ordenes Compra'
        ]);
        Permission::create([
            'name' => 'create_orderPurchaseNormal',
            'description' => 'Crear Ordenes Compra'
        ]);
        Permission::create([
            'name' => 'update_orderPurchaseNormal',
            'description' => 'Modificar Ordenes Compra'
        ]);
        Permission::create([
            'name' => 'destroy_orderPurchaseNormal',
            'description' => 'Eliminar Ordenes Compra'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_orderPurchaseExpress',
            'create_orderPurchaseExpress',
            'update_orderPurchaseExpress',
            'destroy_orderPurchaseExpress',
            'list_orderPurchaseNormal',
            'create_orderPurchaseNormal',
            'update_orderPurchaseNormal',
            'destroy_orderPurchaseNormal',
        ]);
    }
}
