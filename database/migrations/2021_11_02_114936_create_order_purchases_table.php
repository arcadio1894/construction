<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('materials');
            $table->date('date_arrival')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('payment_condition')->nullable();
            $table->enum('currency_order', ['USD', 'PEN'])->nullable()->default('USD');
            $table->decimal('currency_compra', 6,3)->nullable()->default(null);
            $table->decimal('currency_venta', 6,3)->nullable()->default(null);
            $table->decimal('igv', 6,3)->nullable()->default(null);
            $table->decimal('total', 6,3)->nullable()->default(null);
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
        Schema::dropIfExists('order_purchases');
    }
}
