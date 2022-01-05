<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->date('date_delivery')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('payment_condition')->nullable();
            $table->enum('currency_order', ['USD', 'PEN'])->nullable()->default('USD');
            $table->decimal('currency_compra', 6,3)->nullable()->default(null);
            $table->decimal('currency_venta', 6,3)->nullable()->default(null);
            $table->decimal('igv', 9,2)->nullable()->default(null);
            $table->decimal('total', 9,2)->nullable()->default(null);
            $table->string('observation')->nullable()->default('');
            $table->string('quote_supplier')->nullable()->default(null);
            $table->enum('regularize', ['r', 'nr'])->nullable()->default('nr');
            $table->string('image_invoice')->nullable()->default('no_image.png');
            $table->string('image_observation')->nullable()->default('no_image.png');
            $table->enum('deferred_invoice', ['on', 'off'])->default('off');
            $table->softDeletes();
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
        Schema::dropIfExists('order_services');
    }
}
