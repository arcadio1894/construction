<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'name' => 'Banco de Crédito del Perú',
            'short_name' => 'BCP',
            'image' => 'bcp.png'
        ]);
    }
}
