<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionMissingRequestSimpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'myRequest_requestSimple',
            'description' => 'Ver mis solicitudes por área'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'myRequest_requestSimple'
        ]);
    }
}
