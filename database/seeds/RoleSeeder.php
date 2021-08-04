<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        $roleAlmacenero = Role::create([
            'name' => 'almacen',
            'description' => 'Almacén' // Clientes
        ]);

        $permissions = Permission::all();

        foreach ( $permissions as $permission )
        {
            $roleA->givePermissionTo($permission);
        }

        $roleAlmacenero->syncPermissions([1,15,19,24,73,28,32,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,69,77,81,85,89,93,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,115]);
    }
}
