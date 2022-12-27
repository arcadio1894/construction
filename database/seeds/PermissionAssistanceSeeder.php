<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionAssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_assistance',
            'description' => 'Habilitar asistencia'
        ]);
        Permission::create([
            'name' => 'register_assistance',
            'description' => 'Registrar asistencias'
        ]);
        Permission::create([
            'name' => 'report_assistance',
            'description' => 'Reporte asistencias'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_assistance',
            'register_assistance',
            'report_assistance',
        ]);
    }
}
