<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class OrderPurchaseFinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_orderPurchaseFinance',
            'description' => 'Habilitar Ordenes Compra Finanzas'
        ]);
        Permission::create([
            'name' => 'list_orderPurchaseFinance',
            'description' => 'Listar Ordenes Compra Finanzas'
        ]);
        Permission::create([
            'name' => 'create_orderPurchaseFinance',
            'description' => 'Crear Ordenes Compra Finanzas'
        ]);
        Permission::create([
            'name' => 'update_orderPurchaseFinance',
            'description' => 'Modificar Ordenes Compra Finanzas'
        ]);
        Permission::create([
            'name' => 'destroy_orderPurchaseFinance',
            'description' => 'Eliminar Ordenes Compra Finanzas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_orderPurchaseFinance',
            'list_orderPurchaseFinance',
            'create_orderPurchaseFinance',
            'update_orderPurchaseFinance',
            'destroy_orderPurchaseFinance',
        ]);
    }
}
