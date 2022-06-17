<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionReplacementMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'replacement_quote',
            'description' => 'Reemplazar materiales en cotización'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'replacement_quote',
        ]);
    }
}
