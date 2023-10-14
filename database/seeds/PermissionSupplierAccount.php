<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionSupplierAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_supplierAccount',
            'description' => 'Visualizar cuentas bancarias de proveedores'
        ]);
        Permission::create([
            'name' => 'create_supplierAccount',
            'description' => 'Crear cuentas bancarias de proveedores'
        ]);
        Permission::create([
            'name' => 'edit_supplierAccount',
            'description' => 'Editar cuentas bancarias de proveedores'
        ]);
        Permission::create([
            'name' => 'destroy_supplierAccount',
            'description' => 'Eliminar cuentas bancarias de proveedores'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_supplierAccount',
            'create_supplierAccount',
            'edit_supplierAccount',
            'destroy_supplierAccount',
        ]);
    }
}
