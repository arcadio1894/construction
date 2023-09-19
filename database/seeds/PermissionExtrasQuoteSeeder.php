<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionExtrasQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'VBOperations_quote',
            'description' => 'Dar visto bueno de operaciones'
        ]);

        Permission::create([
            'name' => 'VBFinances_quote',
            'description' => 'Dar visto bueno de finanzas'
        ]);

        $roleA = Role::findByName('admin');

        $roleA->givePermissionTo([
            'VBOperations_quote',
            'VBFinances_quote',
        ]);

    }
}
