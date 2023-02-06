<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHourSpecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO: Permisos para Horas Speciales
        Permission::create([
            'name' => 'enable_hourSpecial',
            'description' => 'Habilitar Horas Especiales'
        ]);

        // TODO: Permisos para medicalRest
        Permission::create([
            'name' => 'enable_medicalRest',
            'description' => 'Habilitar Mod. Descansos Médicos'
        ]);
        Permission::create([
            'name' => 'list_medicalRest',
            'description' => 'Listar Descansos Médicos'
        ]);
        Permission::create([
            'name' => 'create_medicalRest',
            'description' => 'Crear Descansos Médicos'
        ]);
        Permission::create([
            'name' => 'edit_medicalRest',
            'description' => 'Editar Descansos Médicos'
        ]);
        Permission::create([
            'name' => 'delete_medicalRest',
            'description' => 'Eliminar Descansos Médicos'
        ]);

        // TODO: Permisos para vacation
        Permission::create([
            'name' => 'enable_vacation',
            'description' => 'Habilitar Mod. Vacaciones'
        ]);
        Permission::create([
            'name' => 'list_vacation',
            'description' => 'Listar Vacaciones'
        ]);
        Permission::create([
            'name' => 'create_vacation',
            'description' => 'Crear Vacaciones'
        ]);
        Permission::create([
            'name' => 'edit_vacation',
            'description' => 'Editar Vacaciones'
        ]);
        Permission::create([
            'name' => 'delete_vacation',
            'description' => 'Eliminar Vacaciones'
        ]);

        // TODO: Permisos para license
        Permission::create([
            'name' => 'enable_license',
            'description' => 'Habilitar Mod. Licencias'
        ]);
        Permission::create([
            'name' => 'list_license',
            'description' => 'Listar Licencias'
        ]);
        Permission::create([
            'name' => 'create_license',
            'description' => 'Crear Licencias'
        ]);
        Permission::create([
            'name' => 'edit_license',
            'description' => 'Editar Licencias'
        ]);
        Permission::create([
            'name' => 'delete_license',
            'description' => 'Eliminar Licencias'
        ]);

        // TODO: Permisos para permit
        Permission::create([
            'name' => 'enable_permit',
            'description' => 'Habilitar Mod. Permisos Trabajadores'
        ]);
        Permission::create([
            'name' => 'list_permit',
            'description' => 'Listar Permisos Trabajadores'
        ]);
        Permission::create([
            'name' => 'create_permit',
            'description' => 'Crear Permisos Trabajadores'
        ]);
        Permission::create([
            'name' => 'edit_permit',
            'description' => 'Editar Permisos Trabajadores'
        ]);
        Permission::create([
            'name' => 'delete_permit',
            'description' => 'Eliminar Permisos Trabajadores'
        ]);

        // TODO: Permisos para suspension
        Permission::create([
            'name' => 'enable_suspension',
            'description' => 'Habilitar Mod. Suspensiones'
        ]);
        Permission::create([
            'name' => 'list_suspension',
            'description' => 'Listar Suspensiones'
        ]);
        Permission::create([
            'name' => 'create_suspension',
            'description' => 'Crear Suspensiones'
        ]);
        Permission::create([
            'name' => 'edit_suspension',
            'description' => 'Editar Suspensiones'
        ]);
        Permission::create([
            'name' => 'delete_suspension',
            'description' => 'Eliminar Suspensiones'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_hourSpecial',
            'enable_medicalRest',
            'list_medicalRest',
            'create_medicalRest',
            'edit_medicalRest',
            'delete_medicalRest',
            'enable_vacation',
            'list_vacation',
            'create_vacation',
            'edit_vacation',
            'delete_vacation',
            'enable_license',
            'list_license',
            'create_license',
            'edit_license',
            'delete_license',
            'enable_permit',
            'list_permit',
            'create_permit',
            'edit_permit',
            'delete_permit',
            'enable_suspension',
            'list_suspension',
            'create_suspension',
            'edit_suspension',
            'delete_suspension',
        ]);
    }
}
