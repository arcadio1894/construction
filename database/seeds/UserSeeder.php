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
            'name' => 'Admin Master',
            'email' => 'admin@sermeind.com',
            'password' => bcrypt('123456789'),
            'image' => '1.jpg',
        ]);

        $userU = User::create([
            'name' => 'Luis Perez',
            'email' => 'luisperez@gmail.com',
            'password' => bcrypt('111111111'),
            'image' => '2.jpg',
        ]);

        $userA->assignRole('admin');
        $userU->assignRole('user');
    }
}
