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
            'list_customer',
            'create_customer',
            'update_customer',
            'destroy_customer',
            'list_supplier',
            'create_supplier',
            'update_supplier',
            'destroy_supplier',
            'assign_supplier',
            'list_category',
            'create_category',
            'update_category',
            'destroy_category',
            'list_materialType',
            'create_materialType',
            'update_materialType',
            'destroy_materialType',
            'list_material',
            'create_material',
            'update_material',
            'destroy_material',
            'list_quote',
            'create_quote',
            'update_quote',
            'destroy_quote',
        ]);
    }
}
