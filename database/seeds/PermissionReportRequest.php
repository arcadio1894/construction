<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionReportRequest extends Seeder
{
    public function run()
    {
        // Crear el permiso
        Permission::create([
            'name' => 'report_request',
            'description' => 'Reporte de solicitudes por área'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'report_request',
        ]);
    }
}
