<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionExportReportSupplierSeeder extends Seeder
{

    public function run()
    {
        Permission::create([
            'name' => 'exportreport_supplier',
            'description' => 'Exportar excel de proveedores'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'exportreport_supplier',
        ]);
    }
}
