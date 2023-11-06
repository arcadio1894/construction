<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionDefaultEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_defaultEquipment',
            'description' => 'Listar equipos por defecto'
        ]);
        Permission::create([
            'name' => 'create_defaultEquipment',
            'description' => 'Crear equipo por defecto'
        ]);
        Permission::create([
            'name' => 'update_defaultEquipment',
            'description' => 'Editar equipo por defecto'
        ]);
        Permission::create([
            'name' => 'destroy_defaultEquipment',
            'description' => 'Eliminar equipo por defecto'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_defaultEquipment',
            'create_defaultEquipment',
            'update_defaultEquipment',
            'destroy_defaultEquipment',
        ]);
    }
    
}
