<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPaymentDeadlineToSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            $table->dropColumn('payment_deadline');
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
        Schema::table('supplier_credits', function (Blueprint $table) {
            //
        });
    }
}
