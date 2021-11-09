<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class InvoicePurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_invoice',
            'description' => 'Listar Facturas Compra'
        ]);
        Permission::create([
            'name' => 'create_invoice',
            'description' => 'Crear Facturas Compra'
        ]);
        Permission::create([
            'name' => 'update_invoice',
            'description' => 'Modificar Facturas Compra'
        ]);
        Permission::create([
            'name' => 'destroy_invoice',
            'description' => 'Eliminar Facturas Compra'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_invoice',
            'create_invoice',
            'update_invoice',
            'destroy_invoice',
        ]);
    }
}
