<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsExtrasToDetailEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_entries', function (Blueprint $table) {
            $table->decimal('largo', 9,2)->nullable();
            $table->decimal('ancho', 9,2)->nullable();
            $table->boolean('scrap')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_entries', function (Blueprint $table) {
            //
        });
    }
}
