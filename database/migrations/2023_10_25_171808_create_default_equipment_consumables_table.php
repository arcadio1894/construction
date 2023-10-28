<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentConsumablesTable extends Migration
{
    /**
     * Run the migrations.
     *
    - id
    - default_equipment_id
    - material_id
    - quantity
    - unit_price
    - total_price
     * @return void
     */
    public function up()
    {
        Schema::create('default_equipment_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_equipment_id')->nullable()->constrained('default_equipments');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->decimal('quantity', 6, 2)->nullable();
            $table->decimal('unit_price', 6, 2)->nullable();
            $table->decimal('total_price', 6, 2)->nullable();
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
        Schema::dropIfExists('default_equipment_consumables');
    }
}
