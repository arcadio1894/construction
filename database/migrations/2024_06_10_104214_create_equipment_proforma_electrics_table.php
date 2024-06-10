<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentProformaElectricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_proforma_electrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_proforma_id')->constrained('equipment_proformas');
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
        Schema::dropIfExists('equipment_proforma_electrics');
    }
}
