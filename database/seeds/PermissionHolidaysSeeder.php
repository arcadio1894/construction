<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'holiday_worker',
            'description' => 'Configurar feriados'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'holiday_worker',
        ]);
    }


}
