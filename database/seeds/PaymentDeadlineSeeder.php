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
            'description' => 'AL CONTADO EN EFECTIVO',
            'days' => 0,
            'type' => 'purchases',
            'credit' => false
        ]);

        PaymentDeadline::create([
            'description' => 'TRANSFERENCIA BANCARIA',
            'days' => 0,
            'type' => 'purchases',
            'credit' => false
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA 7 DIAS',
            'days' => 7,
            'type' => 'purchases',
            'credit' => true
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA 15 DIAS',
            'days' => 15,
            'type' => 'purchases',
            'credit' => true
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA 30 DIAS',
            'days' => 30,
            'type' => 'purchases',
            'credit' => true
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA 45 DIAS',
            'days' => 45,
            'type' => 'purchases',
            'credit' => true
        ]);

        PaymentDeadline::create([
            'description' => 'LETRA 30 DIAS',
            'days' => 30,
            'type' => 'purchases',
            'credit' => true
        ]);

        PaymentDeadline::create([
            'description' => 'AL CONTADO',
            'days' => 0,
            'type' => 'quotes',
            'credit' => false
        ]);

        PaymentDeadline::create([
            'description' => 'FACTORING A 45 DÍAS',
            'days' => 45,
            'type' => 'quotes',
            'credit' => false
        ]);

        PaymentDeadline::create([
            'description' => 'FACTURA A 30 DÍAS',
            'days' => 30,
            'type' => 'quotes',
            'credit' => false
        ]);

        PaymentDeadline::create([
            'description' => '50% DE ADELANTO 25% AL AVANCE - 25% (FACTORING A 45 DÍAS)',
            'days' => 45,
            'type' => 'quotes',
            'credit' => false
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
