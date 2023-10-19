<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsUnpaidLicenseSeeder extends Seeder
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
            'enable_unpaidLicense' => 'Habilitar mod. Licencia sin gozo',
            'list_unpaidLicense' => 'Visualizar licencias sin gozo',
            'create_unpaidLicense' => 'Crear licencias sin gozo',
            'edit_unpaidLicense' => 'Editar licencias sin gozo',
            'delete_unpaidLicense' => 'Eliminar licencias sin gozo',
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
