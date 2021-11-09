<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;

class RoleOperatorLogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleOperator = Role::create([
            'name' => 'operator',
            'description' => 'Operador' // Clientes
        ]);

        $roleLogistic = Role::create([
            'name' => 'logistic',
            'description' => 'Logística' // Clientes
        ]);

        $roleOperator->syncPermissions([1,36,37,38]);

        $roleLogistic->syncPermissions([1,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42]);

        $userOp = User::create([
            'name' => 'Operador',
            'email' => 'operador@sermeind.com',
            'password' => bcrypt('$ermeind2021'),
            'image' => '2.jpg',
        ]);
        $userLo = User::create([
            'name' => 'Logística',
            'email' => 'logistica@sermeind.com',
            'password' => bcrypt('$ermeind2021'),
            'image' => '2.jpg',
        ]);

        $userOp->assignRole('operator');
        $userLo->assignRole('logistic');

    }
}
