<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHourlyPermitSeeder extends Seeder
{

    public function run()
    {
        Permission::create([
            'name' => 'enable_permitHour',
            'description' => 'Habilitar Mod. Permisos por hora Trabajadores'
        ]);
        Permission::create([
            'name' => 'list_permitHour',
            'description' => 'Lista permisos por horas'
        ]);
        Permission::create([
            'name' => 'create_permitHour',
            'description' => 'Crear permisos por horas'
        ]);

        Permission::create([
            'name' => 'edit_permitHour',
            'description' => 'Editar permisos por horas'
        ]);

        Permission::create([
            'name' => 'destroy_permitHour',
            'description' => 'Eliminar permisos por horas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_permitHour',
            'list_permitHour',
            'create_permitHour',
            'edit_permitHour',
            'destroy_permitHour',
            ]);
    }

}
