<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OrderServicePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'show_service',
            'description' => 'Ver módulo servicios'
        ]);
        Permission::create([
            'name' => 'list_service',
            'description' => 'Listar servicios'
        ]);
        Permission::create([
            'name' => 'enable_orderService',
            'description' => 'Habilitar mod. Orden de Servicio'
        ]);
        Permission::create([
            'name' => 'watch_orderService',
            'description' => 'Ver módulo orden de servicios'
        ]);
        Permission::create([
            'name' => 'list_orderService',
            'description' => 'Listar órdenes de servicio'
        ]);
        Permission::create([
            'name' => 'create_orderService',
            'description' => 'Crear órdenes de servicio'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'show_service',
            'list_service',
            'enable_orderService',
            'watch_orderService',
            'list_orderService',
            'create_orderService',
        ]);
    }
}
