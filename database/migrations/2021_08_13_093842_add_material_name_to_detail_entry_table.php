<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialNameToDetailEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_entries', function (Blueprint $table) {
            $table->string('material_name')->nullable();
            $table->string('material_unit')->nullable();
            $table->unsignedBigInteger('material_id')->nullable()->change();
            $table->dropForeign(['material_id']);
            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
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
        Schema::table('detail_entries', function (Blueprint $table) {
            //
        });
    }
}
