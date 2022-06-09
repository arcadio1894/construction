<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RequestPurchasePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_requestPurchaseOperator',
            'description' => 'Listar solicitud de compra'
        ]);
        Permission::create([
            'name' => 'create_requestPurchaseOperator',
            'description' => 'Crear solicitud de compra'
        ]);
        Permission::create([
            'name' => 'edit_requestPurchaseOperator',
            'description' => 'Editar solicitud de compra'
        ]);
        Permission::create([
            'name' => 'delete_requestPurchaseOperator',
            'description' => 'Eliminar solicitud de compra'
        ]);
        Permission::create([
            'name' => 'print_requestPurchaseOperator',
            'description' => 'Imprimir solicitud de compra'
        ]);
        Permission::create([
            'name' => 'confirm_requestPurchaseOperator',
            'description' => 'Confirmar solicitud de compra'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_requestPurchaseOperator',
            'create_requestPurchaseOperator',
            'edit_requestPurchaseOperator',
            'delete_requestPurchaseOperator',
            'print_requestPurchaseOperator',
            'confirm_requestPurchaseOperator'
        ]);
    }
}
