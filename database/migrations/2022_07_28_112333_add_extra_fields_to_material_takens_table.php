<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToMaterialTakensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_takens', function (Blueprint $table) {
            $table->foreignId('equipment_id')
                ->nullable()
                ->constrained('equipments');
            $table->foreignId('output_detail_id')
                ->nullable()
                ->constrained('output_details');
            $table->enum('type_output', ['or' ,'orn', 'ore'])->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_takens', function (Blueprint $table) {
            //
        });
    }
}
