<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentElectricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_equipment_electrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_equipment_id')->constrained('default_equipments');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity', 9, 2)->nullable()->default(0);
            $table->decimal('price', 9, 2)->nullable()->default(0);
            $table->decimal('total', 9, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_equipment_electrics');
    }
}
