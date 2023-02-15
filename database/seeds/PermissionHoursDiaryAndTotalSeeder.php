<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHoursDiaryAndTotalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'hourDiary_assistance',
            'description' => 'Reporte Horas Diarias'
        ]);
        Permission::create([
            'name' => 'totalHours_assistance',
            'description' => 'Reporte Total Horas'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'hourDiary_assistance',
            'totalHours_assistance',
        ]);
    }
}
