<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
    - id
    - default_equipment_id
    - material_id
    - quantity
    - length
    - width
    - percentage
    - unit_price
    - total_price
     */
    public function up()
    {
        Schema::create('default_equipment_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_equipment_id')->nullable()->constrained('default_equipments');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->decimal('quantity', 6, 2)->nullable();
            $table->decimal('length', 6, 2)->nullable();
            $table->decimal('width', 6, 2)->nullable();
            $table->decimal('percentage', 6, 2)->nullable();
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
        Schema::dropIfExists('default_equipment_materials');
    }
}
