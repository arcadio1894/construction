<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_timeline',
            'description' => 'Habilitar cronogramas'
        ]);
        Permission::create([
            'name' => 'index_timeline',
            'description' => 'Ver calendario'
        ]);
        Permission::create([
            'name' => 'create_timeline',
            'description' => 'Gestionar cronograma'
        ]);
        Permission::create([
            'name' => 'show_timeline',
            'description' => 'Ver cronograma'
        ]);
        Permission::create([
            'name' => 'progress_timeline',
            'description' => 'Actualizar progreso de cronograma'
        ]);
        Permission::create([
            'name' => 'download_timeline',
            'description' => 'Descargar cronograma'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_timeline',
            'index_timeline',
            'create_timeline',
            'show_timeline',
            'progress_timeline',
            'download_timeline',
        ]);
    }
}
