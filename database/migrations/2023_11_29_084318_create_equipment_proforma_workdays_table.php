<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentProformaWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_proforma_workdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_proforma_id')->nullable()->constrained('equipment_proformas');
            $table->longText('description')->nullable();
            $table->decimal('quantityPerson', 9, 2)->nullable();
            $table->decimal('hoursPerPerson', 9, 2)->nullable();
            $table->decimal('pricePerHour', 9, 2)->nullable();
            $table->decimal('total_price', 9, 2)->nullable();
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
        Schema::dropIfExists('equipment_proforma_workdays');
    }
}
