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
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);
        Permission::create([
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);
        Permission::create([
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);
        Permission::create([
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);
        Permission::create([
            'name' => 'enableSystems_navbar',
            'description' => 'Habilitar Área Sistemas'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'finishEquipment_quote',
        ]);

        $roleP = Role::findByName('principal');

        $roleP->givePermissionTo([
            'finishEquipment_quote',
        ]);

        $roleL = Role::findByName('logistic');

        $roleL->givePermissionTo([
            'finishEquipment_quote',
        ]);
    }
}
