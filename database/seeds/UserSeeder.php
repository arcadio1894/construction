<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userA = User::create([
            'name' => 'Jorge Gonzales',
            'email' => 'joryes1894@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

        $userU = User::create([
            'name' => 'Luis Perez',
            'email' => 'luisperez@gmail.com',
            'password' => bcrypt('111111111'),
        ]);

        $userA->assignRole('admin');
        $userU->assignRole('user');
    }
}
