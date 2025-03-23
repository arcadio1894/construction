<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->decimal('stock_current', 9,2);
            $table->decimal('quantity_items', 9,2);
            $table->decimal('quantity_entries', 9,2);
            $table->decimal('quantity_outputs', 9,2);
            $table->longText('full_name');
            $table->date('date_check');
            $table->timestamps();

            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_stocks');
    }
}
