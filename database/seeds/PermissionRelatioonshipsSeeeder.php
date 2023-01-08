<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionRelatioonshipsSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'relationship_worker',
            'description' => 'Configurar parentescos'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'relationship_worker',
        ]);
    }
}
