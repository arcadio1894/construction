<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;
use \App\PaymentDeadline;

class PaymentDeadlineSeeder extends Seeder
{
    public function run()
    {
        // Permisos de PaymentDeadlines
        Permission::create([
            'name' => 'enable_paymentDeadline',
            'description' => 'Habilitar Mod. Plazo Pago'
        ]);
        Permission::create([
            'name' => 'list_paymentDeadline',
            'description' => 'Listar plazos de pago'
        ]);
        Permission::create([
            'name' => 'create_paymentDeadline',
            'description' => 'Crear plazos de pago'
        ]);
        Permission::create([
            'name' => 'update_paymentDeadline',
            'description' => 'Editar plazos de pago'
        ]);
        Permission::create([
            'name' => 'destroy_paymentDeadline',
            'description' => 'Eliminar plazos de pago'
        ]);

        // PaymentDeadlines por defecto
        PaymentDeadline::create([
            'description' => 'AL CONTADO',
            'days' => 0
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA A 15 DIAS',
            'days' => 15
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA A 30 DIAS',
            'days' => 30
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA A 45 DIAS',
            'days' => 45
        ]);
        PaymentDeadline::create([
            'description' => 'TRANSFERENCIA BANCARIA',
            'days' => 0
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_paymentDeadline',
            'list_paymentDeadline',
            'create_paymentDeadline',
            'update_paymentDeadline',
            'destroy_paymentDeadline',
        ]);
    }
}
