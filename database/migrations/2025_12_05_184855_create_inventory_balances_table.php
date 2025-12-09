<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->id();
            $table->timestamp('executed_at');           // fecha y hora del cuadre
            $table->unsignedBigInteger('user_id');      // usuario que ejecutó
            $table->unsignedInteger('total_materials')->default(0);
            $table->unsignedInteger('total_entries')->default(0);
            $table->unsignedInteger('total_outputs')->default(0);
            $table->string('excel_path')->nullable();   // ruta del archivo generado (opcional)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_balances');
    }
}
