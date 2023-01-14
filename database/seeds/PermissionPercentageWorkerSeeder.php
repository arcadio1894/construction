<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionPercentageWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'list_percentageWorker',
            'description' => 'Listar porcentajes recursos humanos'
        ]);
        Permission::create([
            'name' => 'create_percentageWorker',
            'description' => 'Crear porcentajes recursos humanos'
        ]);
        Permission::create([
            'name' => 'update_percentageWorker',
            'description' => 'Editar porcentajes recursos humanos'
        ]);
        Permission::create([
            'name' => 'destroy_percentageWorker',
            'description' => 'Eliminar porcentajes recursos humanos'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'list_percentageWorker',
            'create_percentageWorker',
            'update_percentageWorker',
            'destroy_percentageWorker',
        ]);
    }
}
