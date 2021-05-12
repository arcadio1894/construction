<?php

use Illuminate\Database\Seeder;
use App\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'business_name' => 'Danper S.A.C',
            'RUC' => '20170040938',
            'code' => 'C021',
            'contact_name' => 'Ing. Jose Vargas',
            'adress' => 'Carretera Industrial a Laredo S/N',
            'phone' => '910845547',
            'location' => 'Moche - Perú',
            'email' => 'ventas@danper.com',
        ]);
        Customer::create([
            'business_name' => 'Virú S.A.',
            'RUC' => '20373860736',
            'code' => 'C001',
            'contact_name' => 'Ing. Yolving Juárez',
            'adress' => 'Carretera Panamericana Norte Km 521',
            'phone' => '910845547',
            'location' => 'Virú - La Libertad',
            'email' => 'ventas@viru.com.pe',
        ]);
        Customer::create([
            'business_name' => 'Camposol S.A.',
            'RUC' => '20340584237',
            'code' => 'C036',
            'contact_name' => 'Ing. Walter Rodriguez',
            'adress' => 'Av. El Derby #250 Urb. El Derby de Monterrico',
            'phone' => '910845547',
            'location' => 'Santiago de Surco - Perú',
            'email' => 'ventas@camposol.com.pe',
        ]);
    }
}
