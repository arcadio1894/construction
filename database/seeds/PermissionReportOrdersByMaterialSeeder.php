<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionReportOrdersByMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'report_orderPurchaseExpress',
            'description' => 'Reporte de Órdenes por Material'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'report_orderPurchaseExpress',
        ]);

    }
}
