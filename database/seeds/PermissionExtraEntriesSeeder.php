<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionExtraEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'regularizeOrder_entryPurchase',
            'description' => 'Ver detalle de Ent. Compra'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'regularizeOrder_entryPurchase',
        ]);

        $roleL = Role::findByName('logistic');

        $roleL->givePermissionTo([
            'regularizeOrder_entryPurchase',
        ]);
    }
}
