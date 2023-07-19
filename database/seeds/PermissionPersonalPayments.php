<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionPersonalPayments extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_personalPayments',
            'description' => 'Habilitar Mod. Pago de personal'
        ]);
        Permission::create([
            'name' => 'list_personalPayments',
            'description' => 'Listar pago de personal'
        ]);
        Permission::create([
            'name' => 'report_personalPayments',
            'description' => 'Reporte pago de personal'
        ]);
        Permission::create([
            'name' => 'enable_projection',
            'description' => 'Habilitar Mod. Proyección'
        ]);
        Permission::create([
            'name' => 'list_projection',
            'description' => 'Listar Poyecciones'
        ]);
        Permission::create([
            'name' => 'report_projection',
            'description' => 'Reporte proyección'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_personalPayments',
            'list_personalPayments',
            'report_personalPayments',
            'enable_projection',
            'list_projection',
            'report_projection',
        ]);
    }
}
