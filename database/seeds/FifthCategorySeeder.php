<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class FifthCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_fifthCategory',
            'description' => 'Habilitar Mod. Renta Quinta Cat.'
        ]);
        Permission::create([
            'name' => 'list_fifthCategory',
            'description' => 'Listar Renta Quinta Categoría'
        ]);
        Permission::create([
            'name' => 'create_fifthCategory',
            'description' => 'Crear Renta Quinta Categoría'
        ]);
        Permission::create([
            'name' => 'edit_fifthCategory',
            'description' => 'Modificar Renta Quinta Categoría'
        ]);
        Permission::create([
            'name' => 'destroy_fifthCategory',
            'description' => 'Eliminar Renta Quinta Categoría'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_fifthCategory',
            'list_fifthCategory',
            'create_fifthCategory',
            'edit_fifthCategory',
            'destroy_fifthCategory',
        ]);
    }
}
