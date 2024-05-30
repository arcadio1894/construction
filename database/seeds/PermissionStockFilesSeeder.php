<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionStockFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_files',
            'description' => 'Habilitar Importar Archivos Materiales'
        ]);
        Permission::create([
            'name' => 'stock_files',
            'description' => 'Importar archivos de stocks'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_files',
            'stock_files',
        ]);
    }
}
