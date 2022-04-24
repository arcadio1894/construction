<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLengthAndWidthToOutputDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_details', function (Blueprint $table) {
            $table->decimal('length', 9,2)->nullable();
            $table->decimal('width', 9,2)->nullable();
            $table->decimal('price',9,2)->nullable();
            $table->decimal('percentage',9,2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('output_details', function (Blueprint $table) {
            //
        });
    }
}
