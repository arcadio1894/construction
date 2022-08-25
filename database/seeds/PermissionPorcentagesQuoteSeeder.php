<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionPorcentagesQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_porcentageQuote',
            'description' => 'Listar categorías de facturas'
        ]);
        Permission::create([
            'name' => 'create_porcentageQuote',
            'description' => 'Crear categorías de facturas'
        ]);
        Permission::create([
            'name' => 'update_porcentageQuote',
            'description' => 'Editar categorías de facturas'
        ]);
        Permission::create([
            'name' => 'destroy_porcentageQuote',
            'description' => 'Eliminar categorías de facturas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_porcentageQuote',
            'create_porcentageQuote',
            'update_porcentageQuote',
            'destroy_porcentageQuote',
        ]);
    }
}
