<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPurchaseFinanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchase_finance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_purchase_finance_id')->constrained('order_purchase_finances');
            $table->string('material')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity',9,2)->default(0);
            $table->decimal('price',9,2)->default(0);
            $table->decimal('igv',9,2)->default(0);
            $table->decimal('total_detail',9,2)->default(0);
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
        Schema::dropIfExists('order_purchase_finance_details');
    }
}
