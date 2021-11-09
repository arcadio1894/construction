<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class OrderExecutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_orderExecution',
            'description' => 'Listar Ordenes de Ejecución'
        ]);
        Permission::create([
            'name' => 'createOutput_orderExecution',
            'description' => 'Crear Salida de Orden de ejecución'
        ]);
        Permission::create([
            'name' => 'createOutputExtra_orderExecution',
            'description' => 'Crear Salida extra de Orden de ejecución'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_orderExecution',
            'createOutput_orderExecution',
            'createOutputExtra_orderExecution',
        ]);
    }
}
