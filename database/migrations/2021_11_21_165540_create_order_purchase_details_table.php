<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_purchase_id')->constrained('order_purchases');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity',9,2)->default(0);
            $table->decimal('price',9,2)->default(0);
            $table->decimal('igv',9,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_purchase_details');
    }
}
