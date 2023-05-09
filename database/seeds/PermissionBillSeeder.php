<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_bill',
            'description' => 'Habilitar Tipo de Gastos'
        ]);
        Permission::create([
            'name' => 'list_bill',
            'description' => 'Listar Tipo de Gastos'
        ]);
        Permission::create([
            'name' => 'create_bill',
            'description' => 'Crear Tipo de Gastos'
        ]);
        Permission::create([
            'name' => 'update_bill',
            'description' => 'Editar Tipo de Gastos'
        ]);
        Permission::create([
            'name' => 'destroy_bill',
            'description' => 'Eliminar Tipo de Gastos'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_bill',
            'list_bill',
            'create_bill',
            'update_bill',
            'destroy_bill'
        ]);
    }
}
