<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentWorkForcesTable extends Migration
{
    /**
     * Run the migrations.
     *
    - id
    - default_equipment_id
    - description
    - unit_price
    - quantity
    - total_price
    - unit
     * @return void
     */
    public function up()
    {
        Schema::create('default_equipment_work_forces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_equipment_id')->nullable()->constrained('default_equipments');
            $table->longText('description')->nullable();
            $table->decimal('quantity', 6, 2)->nullable();
            $table->decimal('unit_price', 6, 2)->nullable();
            $table->decimal('total_price', 6, 2)->nullable();
            $table->string('unit')->nullable();
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
        Schema::dropIfExists('default_equipment_work_forces');
    }
}
