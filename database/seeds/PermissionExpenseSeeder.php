<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_expense',
            'description' => 'Habilitar Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'list_expense',
            'description' => 'Listar Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'create_expense',
            'description' => 'Crear Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'update_expense',
            'description' => 'Editar Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'destroy_expense',
            'description' => 'Eliminar Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'download_expense',
            'description' => 'Descargar Rendición de Gastos'
        ]);
        Permission::create([
            'name' => 'report_expense',
            'description' => 'Reporte Rendición de Gastos'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_expense',
            'list_expense',
            'create_expense',
            'update_expense',
            'destroy_expense',
            'download_expense',
            'report_expense'
        ]);
    }
}
