<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPurchaseFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchase_finances', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->date('date_delivery')->nullable();
            $table->date('date_order')->nullable();
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
            $table->date('date_invoice')->nullable();
            $table->string('referral_guide')->nullable();
            $table->string('invoice')->nullable();
            $table->foreignId('payment_deadline_id')->nullable()->constrained('payment_deadlines');
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
        Schema::dropIfExists('order_purchase_invoices');
    }
}
