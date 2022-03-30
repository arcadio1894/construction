<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsFollowMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_followMaterials',
            'description' => 'Ver Módulo de seguimiento de materiales'
        ]);
        Permission::create([
            'name' => 'list_followMaterials',
            'description' => 'Listar materiales en seguimiento'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'enable_followMaterials',
            'list_followMaterials',
        ]);

        $roleO = Role::findByName('operator');

        $roleO->givePermissionTo([
            'enable_followMaterials',
            'list_followMaterials',
        ]);
    }
}
