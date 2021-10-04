<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_workdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments');
            $table->decimal('quantityPerson', 9, 2)->nullable()->default(0);
            $table->decimal('hoursPerPerson', 9, 2)->nullable()->default(0);
            $table->decimal('pricePerHour', 9, 2)->nullable()->default(0);
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
        Schema::dropIfExists('equipment_workdays');
    }
}
