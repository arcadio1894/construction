<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionsExtrasQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'show_quote',
            'description' => 'Ver información de cotización'
        ]);
        Permission::create([
            'name' => 'printCustomer_quote',
            'description' => 'Imprimir cotización para cliente'
        ]);
        Permission::create([
            'name' => 'printInternal_quote',
            'description' => 'Imprimir cotización para interna'
        ]);
        Permission::create([
            'name' => 'send_quote',
            'description' => 'Enviar cotización a cliente'
        ]);
        Permission::create([
            'name' => 'finish_quote',
            'description' => 'Finalizar trabajo de cotización'
        ]);
        Permission::create([
            'name' => 'adjust_quote',
            'description' => 'Ajustar porcentajes de cotización'
        ]);
        Permission::create([
            'name' => 'raise_quote',
            'description' => 'Elevar cotización'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'show_quote',
            'printCustomer_quote',
            'printInternal_quote',
            'send_quote',
            'finish_quote',
            'adjust_quote',
            'raise_quote',
        ]);

        $roleL = Role::findByName('logistic');

        $roleL->givePermissionTo([
            'show_quote',
            'printCustomer_quote',
            'printInternal_quote',
            'send_quote',
            'finish_quote',
            'adjust_quote',
            'raise_quote',
        ]);

        $roleP = Role::findByName('principal');

        $roleP->givePermissionTo([
            'show_quote',
            'printCustomer_quote',
            'printInternal_quote',
            'send_quote',
            'finish_quote',
            'adjust_quote',
            'raise_quote',
        ]);
    }
}
