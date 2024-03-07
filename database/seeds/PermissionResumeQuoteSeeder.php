<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionResumeQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'resumen_quote',
            'description' => 'Ver resumen de cotización'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'resumen_quote'
        ]);
    }
}
