<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsReferralGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_referralGuide',
            'description' => 'Ver Módulo de Guías de Remisión'
        ]);
        Permission::create([
            'name' => 'list_referralGuide',
            'description' => 'Listar guías de remisión'
        ]);
        Permission::create([
            'name' => 'create_referralGuide',
            'description' => 'Crear guías de remisión'
        ]);
        Permission::create([
            'name' => 'edit_referralGuide',
            'description' => 'Editar guías de remisión'
        ]);
        Permission::create([
            'name' => 'destroy_referralGuide',
            'description' => 'Anular guías de remisión'
        ]);
        Permission::create([
            'name' => 'download_referralGuide',
            'description' => 'Descargar guías de remisión'
        ]);
        Permission::create([
            'name' => 'print_referralGuide',
            'description' => 'Imprimir guías de remisión'
        ]);
        Permission::create([
            'name' => 'setManagers_referralGuide',
            'description' => 'Modificar responsables de guías de remisión'
        ]);
        Permission::create([
            'name' => 'setReasons_referralGuide',
            'description' => 'Modificar razones de traslado de guías de remisión'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'enable_referralGuide',
            'list_referralGuide',
            'create_referralGuide',
            'edit_referralGuide',
            'destroy_referralGuide',
            'download_referralGuide',
            'print_referralGuide',
            'setManagers_referralGuide',
            'setReasons_referralGuide',
        ]);
    }
}
