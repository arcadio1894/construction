<?php

use Illuminate\Database\Seeder;
use \App\ReasonTransfer;

class ReasonTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReasonTransfer::create(['description' => 'VENTA']);
        ReasonTransfer::create(['description' => 'COMPRA']);
        ReasonTransfer::create(['description' => 'CONSIGNACIÓN']);
        ReasonTransfer::create(['description' => 'DEVOLUCIÓN']);
        ReasonTransfer::create(['description' => 'OTROS']);
        ReasonTransfer::create(['description' => 'SERVICIO']);
    }
}
