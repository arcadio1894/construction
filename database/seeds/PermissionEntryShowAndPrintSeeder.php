<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionEntryShowAndPrintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'watch_entryPurchase',
            'description' => 'Ver detalle de Ent. Compra'
        ]);
        Permission::create([
            'name' => 'print_entryPurchase',
            'description' => 'Imprimir Ent. Compra'
        ]);


        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'watch_entryPurchase',
            'print_entryPurchase',
        ]);
    }
}
