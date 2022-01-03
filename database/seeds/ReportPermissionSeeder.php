<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_report',
            'description' => 'Listar reportes'
        ]);
        Permission::create([
            'name' => 'quote_report',
            'description' => 'Ver reporte de cotizaciones'
        ]);
        Permission::create([
            'name' => 'quoteTotal_report',
            'description' => 'Reporte de cotizaciones Totales'
        ]);
        Permission::create([
            'name' => 'quoteIndividual_report',
            'description' => 'Reporte de cotizaciones Individual'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_report',
            'quote_report',
            'quoteTotal_report',
            'quoteIndividual_report',
        ]);
    }
}
