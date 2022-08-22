<?php

use Illuminate\Database\Seeder;
use \App\CategoryInvoice;

class CategoryInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryInvoice::create([
            'name' => 'UTILES DE OFICINA',
            'description' => 'UTILES DE OFICINA',
        ]);
        CategoryInvoice::create([
            'name' => 'CELEBRACIONES',
            'description' => 'CELEBRACIONES',
        ]);
        CategoryInvoice::create([
            'name' => 'PEAJE',
            'description' => 'CELEBRACIONES',
        ]);
        CategoryInvoice::create([
            'name' => 'SCTR Y SEGURO VIDA LEY',
            'description' => 'SEGURO DE SALUD',
        ]);
        CategoryInvoice::create([
            'name' => 'GASOLINA Y GAS',
            'description' => 'COMBUSTIBLES',
        ]);
        CategoryInvoice::create([
            'name' => 'FLETE',
            'description' => 'FLETE',
        ]);
        CategoryInvoice::create([
            'name' => 'MANTENIMIENTO',
            'description' => 'MANTENIMIENTO',
        ]);
    }
}
