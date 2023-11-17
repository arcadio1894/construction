<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionCustomerReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'reportDownload_customer',
            'description' => 'Descargar Excel de Clientes'
        ]);

        $role = Role::findByName('admin');
        $role->givePermissionTo([
            'reportDownload_customer',
        ]);
    }
}
