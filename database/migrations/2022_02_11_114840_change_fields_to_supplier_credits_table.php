<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsToSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            $table->dropForeign(['entry_id']);
            $table->dropColumn('entry_id');
            $table->unsignedBigInteger('order_purchase_id')->nullable();
            $table->foreign('order_purchase_id')
                ->references('id')
                ->on('order_purchases')
                ->onDelete('SET NULL');
            $table->dropColumn('state');
            $table->dropColumn('state_credit');
            $table->enum('state_credit', ['outstanding', 'expired', 'by_expire', 'paid_out']);
            $table->unsignedBigInteger('order_service_id')->nullable();
            $table->foreign('order_service_id')
                ->references('id')
                ->on('order_services')
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
