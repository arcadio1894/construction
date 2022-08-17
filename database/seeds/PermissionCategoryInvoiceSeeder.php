<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionCategoryInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_categoryInvoice',
            'description' => 'Listar categorías de facturas'
        ]);
        Permission::create([
            'name' => 'create_categoryInvoice',
            'description' => 'Crear categorías de facturas'
        ]);
        Permission::create([
            'name' => 'update_categoryInvoice',
            'description' => 'Editar categorías de facturas'
        ]);
        Permission::create([
            'name' => 'destroy_categoryInvoice',
            'description' => 'Eliminar categorías de facturas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_categoryInvoice',
            'create_categoryInvoice',
            'update_categoryInvoice',
            'destroy_categoryInvoice',
        ]);
    }
}
