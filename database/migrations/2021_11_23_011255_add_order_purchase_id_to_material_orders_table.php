<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderPurchaseIdToMaterialOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_purchase_detail_id')->nullable()->default(null);
            $table->foreign('order_purchase_detail_id')
                ->references('id')
                ->on('order_purchase_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            //
        });
    }
}
