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
            'list_area',
            'create_area',
            'update_area',
            'destroy_area',
            'list_warehouse',
            'create_warehouse',
            'update_warehouse',
            'destroy_warehouse',
            'list_shelf',
            'create_shelf',
            'update_shelf',
            'destroy_shelf',
            'list_level',
            'create_level',
            'update_level',
            'destroy_level',
            'list_container',
            'create_container',
            'update_container',
            'destroy_container',
            'list_position',
            'create_position',
            'update_position',
            'destroy_position',
            'list_location',
            'list_brand',
            'create_brand',
            'update_brand',
            'destroy_brand',
            'list_exampler',
            'create_exampler',
            'update_exampler',
            'destroy_exampler'
        ]);
    }
}
