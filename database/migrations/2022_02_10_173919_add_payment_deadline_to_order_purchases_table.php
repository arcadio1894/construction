<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDeadlineToOrderPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_deadline_id')->nullable();

            $table->foreign('payment_deadline_id')
                ->references('id')
                ->on('payment_deadlines')
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
        Schema::table('order_purchases', function (Blueprint $table) {
            //
        });
    }
}
