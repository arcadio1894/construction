<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsWorkerAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_workerAccount',
            'description' => 'Visualizar cuentas bancarias de trabajadores'
        ]);
        Permission::create([
            'name' => 'create_workerAccount',
            'description' => 'Crear cuentas bancarias de trabajadores'
        ]);
        Permission::create([
            'name' => 'edit_workerAccount',
            'description' => 'Editar cuentas bancarias de trabajadores'
        ]);
        Permission::create([
            'name' => 'destroy_workerAccount',
            'description' => 'Eliminar cuentas bancarias de trabajadores'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_workerAccount',
            'create_workerAccount',
            'edit_workerAccount',
            'destroy_workerAccount',
        ]);
    }
}
