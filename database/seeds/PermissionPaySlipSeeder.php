<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionPaySlipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_paySlip',
            'description' => 'Habilitar Boletas de Pago'
        ]);
        Permission::create([
            'name' => 'list_paySlip',
            'description' => 'Listar Boletas de Pago'
        ]);
        Permission::create([
            'name' => 'create_paySlip',
            'description' => 'Crear Boletas de Pago'
        ]);
        Permission::create([
            'name' => 'edit_paySlip',
            'description' => 'Editar Boletas de Pago'
        ]);
        Permission::create([
            'name' => 'destroy_paySlip',
            'description' => 'Eliminar Boletas de Pago'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_paySlip',
            'list_paySlip',
            'create_paySlip',
            'edit_paySlip',
            'destroy_paySlip'
        ]);
    }
}
