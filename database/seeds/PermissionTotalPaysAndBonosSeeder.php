<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionTotalPaysAndBonosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'totalPays_assistance',
            'description' => 'Reporte de Total a Pagar'
        ]);
        Permission::create([
            'name' => 'enable_bonusRisk',
            'description' => 'Listar bonos de riesgo'
        ]);
        Permission::create([
            'name' => 'list_bonusRisk',
            'description' => 'Listar bonos de riesgo'
        ]);
        Permission::create([
            'name' => 'create_bonusRisk',
            'description' => 'Crear bonos de riesgo'
        ]);
        Permission::create([
            'name' => 'edit_bonusRisk',
            'description' => 'Editar bonos de riesgo'
        ]);
        Permission::create([
            'name' => 'destroy_bonusRisk',
            'description' => 'Eliminar bonos de riesgo'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'totalPays_assistance',
            'enable_bonusRisk',
            'list_bonusRisk',
            'create_bonusRisk',
            'edit_bonusRisk',
            'destroy_bonusRisk',
        ]);
    }
}
