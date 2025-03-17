<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsExtrasToOrderPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_purchase_details', function (Blueprint $table) {
            $table->decimal('largo', 9,2)->nullable();
            $table->decimal('ancho', 9,2)->nullable();
            $table->boolean('scrap')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_purchase_details', function (Blueprint $table) {
            //
        });
    }
}
