<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsAlertStockMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'stock_followMaterials',
            'description' => 'Ver alerta de stock de materiales'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'stock_followMaterials',
        ]);
    }
}
