<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsNullableToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('description_quote')->nullable()->change();
            $table->string('date_quote')->nullable()->change();
            $table->string('date_validate')->nullable()->change();
            $table->string('way_to_pay')->nullable()->change();
            $table->string('delivery_time')->nullable()->change();
            $table->unsignedBigInteger('customer_id')->nullable()->change();
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            //
        });
    }
}
