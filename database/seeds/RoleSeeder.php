<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role Administrador
        $roleA = Role::create([
            'name' => 'admin',
            'description' => 'Administrador'
        ]);

        $roleU = Role::create([
            'name' => 'user',
            'description' => 'Usuario' // Clientes
        ]);

        $roleA->givePermissionTo([
            'access_dashboard',
            'access_permission',
            'list_user',
            'create_user',
            'update_user',
            'destroy_user',
            'list_role',
            'create_role',
            'update_role',
            'destroy_role',
            'list_permission',
            'create_permission',
            'update_permission',
            'destroy_permission',
        ]);
    }
}
