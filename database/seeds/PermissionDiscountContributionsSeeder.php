<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionDiscountContributionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_discountContribution',
            'description' => 'Habilitar Mod. Descuentos y Aportes'
        ]);
        Permission::create([
            'name' => 'enable_discount',
            'description' => 'Habilitar Mod. Descuentos'
        ]);
        Permission::create([
            'name' => 'list_discount',
            'description' => 'Listar descuentos'
        ]);
        Permission::create([
            'name' => 'create_discount',
            'description' => 'Crear descuentos'
        ]);
        Permission::create([
            'name' => 'edit_discount',
            'description' => 'Modificar descuentos'
        ]);
        Permission::create([
            'name' => 'destroy_discount',
            'description' => 'Eliminar descuentos'
        ]);
        Permission::create([
            'name' => 'enable_refund',
            'description' => 'Habilitar Mod. Reembolso'
        ]);
        Permission::create([
            'name' => 'list_refund',
            'description' => 'Listar reembolsos'
        ]);
        Permission::create([
            'name' => 'create_refund',
            'description' => 'Crear reembolsos'
        ]);
        Permission::create([
            'name' => 'edit_refund',
            'description' => 'Modificar reembolsos'
        ]);
        Permission::create([
            'name' => 'destroy_refund',
            'description' => 'Eliminar reembolsos'
        ]);
        Permission::create([
            'name' => 'enable_loan',
            'description' => 'Habilitar Mod. Préstamos'
        ]);
        Permission::create([
            'name' => 'list_loan',
            'description' => 'Listar Préstamos'
        ]);
        Permission::create([
            'name' => 'create_loan',
            'description' => 'Crear Préstamos'
        ]);
        Permission::create([
            'name' => 'edit_loan',
            'description' => 'Modificar Préstamos'
        ]);
        Permission::create([
            'name' => 'destroy_loan',
            'description' => 'Eliminar Préstamos'
        ]);
        Permission::create([
            'name' => 'enable_gratification',
            'description' => 'Habilitar Mod. Gratificación'
        ]);
        Permission::create([
            'name' => 'list_gratification',
            'description' => 'Listar Gratificación'
        ]);
        Permission::create([
            'name' => 'create_gratification',
            'description' => 'Crear Gratificación'
        ]);
        Permission::create([
            'name' => 'edit_gratification',
            'description' => 'Modificar Gratificación'
        ]);
        Permission::create([
            'name' => 'destroy_gratification',
            'description' => 'Eliminar Gratificación'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_discountContribution',
            'enable_discount',
            'list_discount',
            'create_discount',
            'edit_discount',
            'destroy_discount',
            'enable_refund',
            'list_refund',
            'create_refund',
            'edit_refund',
            'destroy_refund',
            'enable_loan',
            'list_loan',
            'create_loan',
            'edit_loan',
            'destroy_loan',
            'enable_gratification',
            'list_gratification',
            'create_gratification',
            'edit_gratification',
            'destroy_gratification',
        ]);
    }
}
