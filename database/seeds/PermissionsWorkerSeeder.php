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
            'name' => 'destroy_worker',
            'description' => 'Eliminar colaborador'
        ]);
        Permission::create([
            'name' => 'restore_worker',
            'description' => 'Habilitar colaborador'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_worker',
            'list_worker',
            'create_worker',
            'edit_worker',
            'destroy_worker',
            'restore_worker',
        ]);
    }
}
