<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionOrderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'update_orderService',
            'description' => 'Editar órdenes de servicio'
        ]);
        Permission::create([
            'name' => 'delete_orderService',
            'description' => 'Anular órdenes de servicio'
        ]);
        Permission::create([
            'name' => 'regularize_orderService',
            'description' => 'Regularizar órdenes de servicio'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'update_orderService',
            'delete_orderService',
            'regularize_orderService',
        ]);
    }
}
