<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentProformaWorkforcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_proforma_workforces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_proforma_id')->nullable()->constrained('equipment_proformas');
            $table->longText('description')->nullable();
            $table->decimal('quantity', 9, 2)->nullable();
            $table->decimal('unit_price', 9, 2)->nullable();
            $table->decimal('total_price', 9, 2)->nullable();
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
        Schema::dropIfExists('equipment_proforma_workforces');
    }
}
