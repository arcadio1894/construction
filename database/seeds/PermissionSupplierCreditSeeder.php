<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionSupplierCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_credit',
            'description' => 'Habilitar módulo créditos'
        ]);
        Permission::create([
            'name' => 'control_credit',
            'description' => 'Listar control de créditos'
        ]);
        Permission::create([
            'name' => 'edit_credit',
            'description' => 'Editar crédito'
        ]);
        Permission::create([
            'name' => 'pay_credit',
            'description' => 'Pagar crédito'
        ]);
        Permission::create([
            'name' => 'nopay_credit',
            'description' => 'Anular pago de crédito'
        ]);
        Permission::create([
            'name' => 'destroy_credit',
            'description' => 'Eliminar crédito'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_credit',
            'control_credit',
            'edit_credit',
            'pay_credit',
            'nopay_credit',
            'destroy_credit',
        ]);
    }
}
