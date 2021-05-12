<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'access_dashboard',
            'description' => 'Acceder al dashboard' // Permiso para acceder al dashboard
        ]);
        // Módulo Permisos
        Permission::create([
            'name' => 'access_permission',
            'description' => 'Gestionar Roles y Permisos' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'list_user',
            'description' => 'Listar usuarios' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'create_user',
            'description' => 'Crear usuarios' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'update_user',
            'description' => 'Modificar usuarios' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'destroy_user',
            'description' => 'Eliminar usuarios' // Permiso para gestionar roles y permisos
        ]);

        Permission::create([
            'name' => 'list_role',
            'description' => 'Listar Roles' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'create_role',
            'description' => 'Crear roles' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'update_role',
            'description' => 'Modificar roles' // Permiso para gestionar roles y permisos
        ]);
        Permission::create([
            'name' => 'destroy_role',
            'description' => 'Eliminar roles' // Permiso para gestionar roles y permisos
        ]);

        Permission::create([
            'name' => 'list_permission',
            'description' => 'Listar Permisos'
        ]);
        Permission::create([
            'name' => 'create_permission',
            'description' => 'Crear Permisos'
        ]);
        Permission::create([
            'name' => 'update_permission',
            'description' => 'Modificar Permisos'
        ]);
        Permission::create([
            'name' => 'destroy_permission',
            'description' => 'Eliminar Permisos'
        ]);

        Permission::create([
            'name' => 'list_customer',
            'description' => 'Listar Clientes'
        ]);
        Permission::create([
            'name' => 'create_customer',
            'description' => 'Crear Clientes'
        ]);
        Permission::create([
            'name' => 'update_customer',
            'description' => 'Modificar Clientes'
        ]);
        Permission::create([
            'name' => 'destroy_customer',
            'description' => 'Eliminar Clientes'
        ]);

        Permission::create([
            'name' => 'list_supplier',
            'description' => 'Listar Proveedores'
        ]);
        Permission::create([
            'name' => 'create_supplier',
            'description' => 'Crear Proveedores'
        ]);
        Permission::create([
            'name' => 'update_supplier',
            'description' => 'Modificar Proveedores'
        ]);
        Permission::create([
            'name' => 'destroy_supplier',
            'description' => 'Eliminar Proveedores'
        ]);
        Permission::create([
            'name' => 'assign_supplier',
            'description' => 'Proveedores y Materiales'
        ]);

        Permission::create([
            'name' => 'list_category',
            'description' => 'Listar Categoría'
        ]);
        Permission::create([
            'name' => 'create_category',
            'description' => 'Crear Categoría'
        ]);
        Permission::create([
            'name' => 'update_category',
            'description' => 'Modificar Categoría'
        ]);
        Permission::create([
            'name' => 'destroy_category',
            'description' => 'Eliminar Categoría'
        ]);

        Permission::create([
            'name' => 'list_materialType',
            'description' => 'Listar Tipos de Materiales'
        ]);
        Permission::create([
            'name' => 'create_materialType',
            'description' => 'Crear Tipos de Materiales'
        ]);
        Permission::create([
            'name' => 'update_materialType',
            'description' => 'Modificar Tipos de Materiales'
        ]);
        Permission::create([
            'name' => 'destroy_materialType',
            'description' => 'Eliminar Tipos de Materiales'
        ]);

        Permission::create([
            'name' => 'list_material',
            'description' => 'Listar Materiales'
        ]);
        Permission::create([
            'name' => 'create_material',
            'description' => 'Crear Materiales'
        ]);
        Permission::create([
            'name' => 'update_material',
            'description' => 'Modificar Materiales'
        ]);
        Permission::create([
            'name' => 'destroy_material',
            'description' => 'Eliminar Materiales'
        ]);

        Permission::create([
            'name' => 'list_quote',
            'description' => 'Listar Cotizaciones'
        ]);
        Permission::create([
            'name' => 'create_quote',
            'description' => 'Crear Cotizaciones'
        ]);
        Permission::create([
            'name' => 'update_quote',
            'description' => 'Modificar Cotizaciones'
        ]);
        Permission::create([
            'name' => 'destroy_quote',
            'description' => 'Eliminar Cotizaciones'
        ]);
    }
}
