<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentWorkDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
    - id
    - default_equipment_id
    - description
    - quantityPerson
    - hoursPerPerson
    - pricePerHour
    - total_price
     * @return void
     */
    public function up()
    {
        Schema::create('default_equipment_work_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_equipment_id')->nullable()->constrained('default_equipments');
            $table->longText('description')->nullable();
            $table->decimal('quantityPerson', 6, 2)->nullable();
            $table->decimal('hoursPerPerson', 6, 2)->nullable();
            $table->decimal('pricePerHour', 6, 2)->nullable();
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
        Schema::dropIfExists('default_equipment_work_days');
    }
}
