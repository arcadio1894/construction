<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity', 9,2)->nullable();
            $table->decimal('length', 9,2)->nullable();
            $table->decimal('width', 9,2)->nullable();
            $table->decimal('percentage', 9,2)->nullable();
            $table->enum('state', ['En compra', 'Falta comprar']);
            $table->decimal('price', 9,2);
            $table->decimal('total', 9,2);
            $table->enum('availability', ['Agotado', 'Completo']);
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
        Schema::dropIfExists('equipment_materials');
    }
}
