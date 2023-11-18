<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionEntriesPurchaseReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'showReportEntries_entryPurchase',
            'description' => 'Listar reporte de Entradas'
        ]);
        Permission::create([
            'name' => 'searchReportEntries_entryPurchase',
            'description' => 'Buscar reporte de Entradas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'showReportEntries_entryPurchase',
            'searchReportEntries_entryPurchase',

        ]);
    }
    
}