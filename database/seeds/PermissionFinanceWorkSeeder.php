<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionFinanceWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_financeWorks',
            'description' => 'Habilitar Mod. Trabajos Finanzas'
        ]);
        Permission::create([
            'name' => 'list_financeWorks',
            'description' => 'Listar Trabajos Finanzas'
        ]);
        Permission::create([
            'name' => 'update_financeWorks',
            'description' => 'Modificar Trabajos Finanzas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_financeWorks',
            'list_financeWorks',
            'update_financeWorks',
        ]);
    }
}
