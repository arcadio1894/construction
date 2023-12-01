<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyCreatorToProformasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->enum('currency', ['USD', 'PEN'])->nullable()->default(null);
            $table->decimal('currency_compra', 9,3)->nullable()->default(null);
            $table->decimal('currency_venta', 9,3)->nullable()->default(null);
            $table->decimal('total_soles', 9,2)->nullable()->default(0);
            $table->longText('observations')->nullable();
            $table->foreignId('user_creator')->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proformas', function (Blueprint $table) {
            //
        });
    }
}
