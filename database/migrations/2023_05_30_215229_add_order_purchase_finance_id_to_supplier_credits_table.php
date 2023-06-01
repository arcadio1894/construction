<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderPurchaseFinanceIdToSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            $table->unsignedBigInteger('order_purchase_finance_id')->nullable();
            $table->foreign('order_purchase_finance_id')
                ->references('id')
                ->on('order_purchase_finances')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            //
        });
    }
}
