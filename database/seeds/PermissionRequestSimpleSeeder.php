<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRequestSimpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_requestSimple',
            'description' => 'Habilitar solicitud por área'
        ]);
        Permission::create([
            'name' => 'list_requestSimple',
            'description' => 'Listar solicitud de área'
        ]);
        Permission::create([
            'name' => 'create_requestSimple',
            'description' => 'Crear solicitud de área'
        ]);
        Permission::create([
            'name' => 'edit_requestSimple',
            'description' => 'Editar solicitud de área'
        ]);
        Permission::create([
            'name' => 'delete_requestSimple',
            'description' => 'Eliminar solicitud de área'
        ]);
        Permission::create([
            'name' => 'attend_requestSimple',
            'description' => 'Confirmar solicitud de área'
        ]);
        Permission::create([
            'name' => 'confirm_requestSimple',
            'description' => 'Confirmar solicitud de área'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_requestSimple',
            'list_requestSimple',
            'create_requestSimple',
            'edit_requestSimple',
            'delete_requestSimple',
            'attend_requestSimple',
            'confirm_requestSimple'
        ]);
    }
}
