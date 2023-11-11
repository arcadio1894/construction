<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionDownloadTotalPaysAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::findByName('admin');

        $permissions = [
            'downloadTotalPaysAccounts_assistance' => 'Descargar Rep. de Total a Pagar con Cuentas',
        ];
        foreach ($permissions as $permissionName => $description) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['description' => $description, 'guard_name' => 'web']
            );

            $role->givePermissionTo($permission);
        }
    }
}
