<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAlimonySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_alimony',
            'description' => 'Habilitar Pensión Alimentos'
        ]);
        Permission::create([
            'name' => 'list_alimony',
            'description' => 'Listar Pensión Alimentos'
        ]);
        Permission::create([
            'name' => 'create_alimony',
            'description' => 'Crear Pensión Alimentos'
        ]);
        Permission::create([
            'name' => 'edit_alimony',
            'description' => 'Editar Pensión Alimentos'
        ]);
        Permission::create([
            'name' => 'destroy_alimony',
            'description' => 'Eliminar Pensión Alimentoss'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_alimony',
            'list_alimony',
            'create_alimony',
            'edit_alimony',
            'destroy_alimony'
        ]);
    }
}
