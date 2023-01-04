<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionNavBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);
        Permission::create([
            'name' => 'enableLogistic_navbar',
            'description' => 'Habilitar Área Logistica'
        ]);
        Permission::create([
            'name' => 'enableOperator_navbar',
            'description' => 'Habilitar Área Operaciones'
        ]);
        Permission::create([
            'name' => 'enableAlmacen_navbar',
            'description' => 'Habilitar Área Almacen'
        ]);
        Permission::create([
            'name' => 'enableResourcesHumans_navbar',
            'description' => 'Habilitar Área RRHH'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'enableSystems_navbar',
            'enableLogistic_navbar',
            'enableOperator_navbar',
            'enableAlmacen_navbar',
            'enableResourcesHumans_navbar',
        ]);

        $roleP = Role::findByName('principal');

        $roleP->givePermissionTo([
            'enableSystems_navbar',
            'enableLogistic_navbar',
            'enableOperator_navbar',
            'enableAlmacen_navbar',
            'enableResourcesHumans_navbar',
        ]);

        $roleL = Role::findByName('logistic');

        $roleL->givePermissionTo([
            'enableLogistic_navbar',
        ]);

        $roleO = Role::findByName('operator');

        $roleO->givePermissionTo([
            'enableOperator_navbar',
        ]);

        $roleA = Role::findByName('almacen');

        $roleA->givePermissionTo([
            'enableAlmacen_navbar',
        ]);

        $roleRH = Role::findByName('resources_humans');

        $roleRH->givePermissionTo([
            'enableResourcesHumans_navbar',
        ]);
    }
}
