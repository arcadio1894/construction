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
        Permission::create([
            'name' => 'confirm_quote',
            'description' => 'Confirmar Cotizaciones'
        ]);
        Permission::create([
            'name' => 'renew_quote',
            'description' => 'Renovar Cotizaciones'
        ]);

        Permission::create([
            'name' => 'list_area',
            'description' => 'Listar Áreas'
        ]);
        Permission::create([
            'name' => 'create_area',
            'description' => 'Crear Área'
        ]);
        Permission::create([
            'name' => 'update_area',
            'description' => 'Modificar Área'
        ]);
        Permission::create([
            'name' => 'destroy_area',
            'description' => 'Eliminar Área'
        ]);

        Permission::create([
            'name' => 'list_warehouse',
            'description' => 'Listar Almacenes'
        ]);
        Permission::create([
            'name' => 'create_warehouse',
            'description' => 'Crear Almacén'
        ]);
        Permission::create([
            'name' => 'update_warehouse',
            'description' => 'Modificar Almacén'
        ]);
        Permission::create([
            'name' => 'destroy_warehouse',
            'description' => 'Eliminar Almacén'
        ]);

        Permission::create([
            'name' => 'list_shelf',
            'description' => 'Listar Anaqueles'
        ]);
        Permission::create([
            'name' => 'create_shelf',
            'description' => 'Crear Anaquel'
        ]);
        Permission::create([
            'name' => 'update_shelf',
            'description' => 'Modificar Anaquel'
        ]);
        Permission::create([
            'name' => 'destroy_shelf',
            'description' => 'Eliminar Anaquel'
        ]);

        Permission::create([
            'name' => 'list_level',
            'description' => 'Listar Niveles'
        ]);
        Permission::create([
            'name' => 'create_level',
            'description' => 'Crear Niveles'
        ]);
        Permission::create([
            'name' => 'update_level',
            'description' => 'Modificar Niveles'
        ]);
        Permission::create([
            'name' => 'destroy_level',
            'description' => 'Eliminar Niveles'
        ]);

        Permission::create([
            'name' => 'list_container',
            'description' => 'Listar Contenedores'
        ]);
        Permission::create([
            'name' => 'create_container',
            'description' => 'Crear Contenedor'
        ]);
        Permission::create([
            'name' => 'update_container',
            'description' => 'Modificar Contenedor'
        ]);
        Permission::create([
            'name' => 'destroy_container',
            'description' => 'Eliminar Contenedor'
        ]);

        Permission::create([
            'name' => 'list_position',
            'description' => 'Listar Posiciones'
        ]);
        Permission::create([
            'name' => 'create_position',
            'description' => 'Crear Posición'
        ]);
        Permission::create([
            'name' => 'update_position',
            'description' => 'Modificar Posición'
        ]);
        Permission::create([
            'name' => 'destroy_position',
            'description' => 'Eliminar Posición'
        ]);

        Permission::create([
            'name' => 'list_location',
            'description' => 'Ver ubicaciones'
        ]);

        Permission::create([
            'name' => 'list_brand',
            'description' => 'Listar Marca'
        ]);
        Permission::create([
            'name' => 'create_brand',
            'description' => 'Crear Marca'
        ]);
        Permission::create([
            'name' => 'update_brand',
            'description' => 'Modificar Marca'
        ]);
        Permission::create([
            'name' => 'destroy_brand',
            'description' => 'Eliminar Marca'
        ]);

        Permission::create([
            'name' => 'list_exampler',
            'description' => 'Listar Modelo'
        ]);
        Permission::create([
            'name' => 'create_exampler',
            'description' => 'Crear Modelo'
        ]);
        Permission::create([
            'name' => 'update_exampler',
            'description' => 'Modificar Modelo'
        ]);
        Permission::create([
            'name' => 'destroy_exampler',
            'description' => 'Eliminar Modelo'
        ]);

        Permission::create([
            'name' => 'list_subcategory',
            'description' => 'Listar Subcategorías'
        ]);
        Permission::create([
            'name' => 'create_subcategory',
            'description' => 'Crear Subcategorías'
        ]);
        Permission::create([
            'name' => 'update_subcategory',
            'description' => 'Modificar Subcategorías'
        ]);
        Permission::create([
            'name' => 'destroy_subcategory',
            'description' => 'Eliminar Subcategorías'
        ]);

        Permission::create([
            'name' => 'list_subType',
            'description' => 'Listar SubTipos'
        ]);
        Permission::create([
            'name' => 'create_subType',
            'description' => 'Crear SubTipos'
        ]);
        Permission::create([
            'name' => 'update_subType',
            'description' => 'Modificar SubTipos'
        ]);
        Permission::create([
            'name' => 'destroy_subType',
            'description' => 'Eliminar SubTipos'
        ]);

        Permission::create([
            'name' => 'list_warrant',
            'description' => 'Listar cédulas'
        ]);
        Permission::create([
            'name' => 'create_warrant',
            'description' => 'Crear cédulas'
        ]);
        Permission::create([
            'name' => 'update_warrant',
            'description' => 'Modificar cédulas'
        ]);
        Permission::create([
            'name' => 'destroy_warrant',
            'description' => 'Eliminar cédulas'
        ]);

        Permission::create([
            'name' => 'list_quality',
            'description' => 'Listar calidades'
        ]);
        Permission::create([
            'name' => 'create_quality',
            'description' => 'Crear calidades'
        ]);
        Permission::create([
            'name' => 'update_quality',
            'description' => 'Modificar calidades'
        ]);
        Permission::create([
            'name' => 'destroy_quality',
            'description' => 'Eliminar calidades'
        ]);

        Permission::create([
            'name' => 'list_unitMeasure',
            'description' => 'Listar Und. Medidas'
        ]);
        Permission::create([
            'name' => 'create_unitMeasure',
            'description' => 'Crear Und. Medidas'
        ]);
        Permission::create([
            'name' => 'update_unitMeasure',
            'description' => 'Modificar Und. Medidas'
        ]);
        Permission::create([
            'name' => 'destroy_unitMeasure',
            'description' => 'Eliminar Und. Medidas'
        ]);

        Permission::create([
            'name' => 'list_contactName',
            'description' => 'Listar Contacto'
        ]);
        Permission::create([
            'name' => 'create_contactName',
            'description' => 'Crear Contacto'
        ]);
        Permission::create([
            'name' => 'update_contactName',
            'description' => 'Modificar Contacto'
        ]);
        Permission::create([
            'name' => 'destroy_contactName',
            'description' => 'Eliminar Contacto'
        ]);

        Permission::create([
            'name' => 'list_transfer',
            'description' => 'Listar Transferencias'
        ]);
        Permission::create([
            'name' => 'create_transfer',
            'description' => 'Crear Transferencias'
        ]);
        Permission::create([
            'name' => 'update_transfer',
            'description' => 'Modificar Transferencias'
        ]);
        Permission::create([
            'name' => 'destroy_transfer',
            'description' => 'Eliminar Transferencias'
        ]);

        Permission::create([
            'name' => 'list_typeScrap',
            'description' => 'Listar tipos de retacería'
        ]);
        Permission::create([
            'name' => 'create_typeScrap',
            'description' => 'Crear tipos de retacería'
        ]);
        Permission::create([
            'name' => 'update_typeScrap',
            'description' => 'Modificar tipos de retacería'
        ]);
        Permission::create([
            'name' => 'destroy_typeScrap',
            'description' => 'Eliminar tipos de retacería'
        ]);

        // Entries
        Permission::create([
            'name' => 'list_entryPurchase',
            'description' => 'Listar Ent. Compra'
        ]);
        Permission::create([
            'name' => 'create_entryPurchase',
            'description' => 'Crear Ent. Compra'
        ]);
        Permission::create([
            'name' => 'update_entryPurchase',
            'description' => 'Modificar Ent. Compra'
        ]);
        Permission::create([
            'name' => 'destroy_entryPurchase',
            'description' => 'Eliminar Ent. Compra'
        ]);

        Permission::create([
            'name' => 'list_entryScrap',
            'description' => 'Listar Retacería'
        ]);
        Permission::create([
            'name' => 'create_entryScrap',
            'description' => 'Crear Retacería'
        ]);
        Permission::create([
            'name' => 'update_entryScrap',
            'description' => 'Modificar Retacería'
        ]);
        Permission::create([
            'name' => 'destroy_entryScrap',
            'description' => 'Eliminar Retacería'
        ]);

        // Outputs
        Permission::create([
            'name' => 'list_request',
            'description' => 'Listar Solicitudes'
        ]);
        Permission::create([
            'name' => 'create_request',
            'description' => 'Crear Solicitud'
        ]);
        Permission::create([
            'name' => 'attend_request',
            'description' => 'Atender Solicitud'
        ]);
        Permission::create([
            'name' => 'destroy_request',
            'description' => 'Eliminar Solicitud'
        ]);

        Permission::create([
            'name' => 'list_output',
            'description' => 'Listar Salidas'
        ]);

        Permission::create([
            'name' => 'confirm_output',
            'description' => 'Confirmar Salidas'
        ]);
        Permission::create([
            'name' => 'destroy_output',
            'description' => 'Eliminar Salidas'
        ]);

    }
}
