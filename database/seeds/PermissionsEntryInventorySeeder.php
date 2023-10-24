<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsEntryInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'create_entryInventory',
            'description' => 'Crear Ent. Inventario' 
        ]);
        Permission::create([
            'name' => 'list_entryInventory',
            'description' => 'Listar Ent. Inventario' 
        ]);
        Permission::create([
            'name' => 'update_entryInventory',
            'description' => 'Modificar Ent. Compra' 
        ]);
        Permission::create([
            'name' => 'destroy_entryInventory',
            'description' => 'Eliminar Ent. Inventario' 
        ]);
    }
}
