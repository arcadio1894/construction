<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_worker',
            'description' => 'Habilitar mod. Colaboradores'
        ]);
        Permission::create([
            'name' => 'list_worker',
            'description' => 'Listar colaboradores'
        ]);
        Permission::create([
            'name' => 'create_worker',
            'description' => 'Crear colaborador'
        ]);
        Permission::create([
            'name' => 'edit_worker',
            'description' => 'Editar colaborador'
        ]);
        Permission::create([
            'name' => 'destroy_worker',
            'description' => 'Eliminar colaborador'
        ]);
        Permission::create([
            'name' => 'restore_worker',
            'description' => 'Habilitar colaborador'
        ]);

        Permission::create([
            'name' => 'enableConfig_worker',
            'description' => 'Habilitar config. colaboradores'
        ]);
        Permission::create([
            'name' => 'contract_worker',
            'description' => 'Configurar contratos'
        ]);
        Permission::create([
            'name' => 'statusCivil_worker',
            'description' => 'Configurar estados civiles'
        ]);
        Permission::create([
            'name' => 'function_worker',
            'description' => 'Configurar funciones'
        ]);
        Permission::create([
            'name' => 'systemPension_worker',
            'description' => 'Configurar sistemas de pension'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_worker',
            'list_worker',
            'create_worker',
            'edit_worker',
            'destroy_worker',
            'restore_worker',
            'enableConfig_worker',
            'contract_worker',
            'statusCivil_worker',
            'function_worker',
            'systemPension_worker',
        ]);
    }
}
