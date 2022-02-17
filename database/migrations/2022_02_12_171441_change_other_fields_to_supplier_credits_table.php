<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOtherFieldsToSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            $table->enum('state_credit', ['outstanding', 'expired', 'by_expire', 'paid_out']);
            $table->unsignedBigInteger('order_service_id')->nullable();
            $table->foreign('order_service_id')
                ->references('id')
                ->on('order_services')
                ->onDelete('SET NULL');
            $table->string('code_order')->nullable();
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
