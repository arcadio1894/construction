<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class AreaWorkerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_areaWorker',
            'description' => 'Listar área de empresa'
        ]);
        Permission::create([
            'name' => 'create_areaWorker',
            'description' => 'Crear área de empresa'
        ]);
        Permission::create([
            'name' => 'update_areaWorker',
            'description' => 'Editar área de empresa'
        ]);
        Permission::create([
            'name' => 'destroy_areaWorker',
            'description' => 'Eliminar área de empresa'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_areaWorker',
            'create_areaWorker',
            'update_areaWorker',
            'destroy_areaWorker',
        ]);
    }
}
