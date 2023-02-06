<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionReportOutputSimpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'report_requestSimple',
            'description' => 'Ver reporte solicitudes por área'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'report_requestSimple'
        ]);
    }
}
