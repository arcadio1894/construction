<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionExpenseSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_expenseSupplier',
            'description' => 'Habilitar egresos proveedores'
        ]);

        Permission::create([
            'name' => 'list_expenseSupplier',
            'description' => 'Listar egresos proveedores'
        ]);

        Permission::create([
            'name' => 'export_expenseSupplier',
            'description' => 'Exportar egresos proveedores'
        ]);

        Permission::create([
            'name' => 'modify_expenseSupplier',
            'description' => 'Modificar egresos proveedores'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_expenseSupplier',
            'list_expenseSupplier',
            'export_expenseSupplier',
            'modify_expenseSupplier',
        ]);
    }
}
