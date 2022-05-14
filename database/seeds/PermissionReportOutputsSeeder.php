<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionReportOutputsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'report_output',
            'description' => 'Ver reporte de materiales en salida'
        ]);


        $roleA = Role::findByName('admin');
        $roleAl= Role::findByName('almacen');

        $roleA->givePermissionTo([
            'report_output',
        ]);
        $roleAl->givePermissionTo([
            'report_output',
        ]);
    }
}
