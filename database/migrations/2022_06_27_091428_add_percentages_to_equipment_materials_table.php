<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentagesToEquipmentMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment_materials', function (Blueprint $table) {
            $table->decimal('utility', 5, 2)->default(0);
            $table->decimal('letter', 5, 2)->default(0);
            $table->decimal('rent', 5, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_materials', function (Blueprint $table) {
            //
        });
    }
}
