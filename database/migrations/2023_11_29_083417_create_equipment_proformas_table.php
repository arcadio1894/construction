<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentProformasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_proformas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->nullable()->constrained('proformas')->nullOnDelete();
            $table->foreignId('default_equipment_id')->nullable()->constrained('default_equipments')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('detail')->nullable();
            $table->integer('quantity')->nullable()->default(1);
            $table->decimal('total', 9,2)->default(0);
            $table->decimal('utility', 9,2)->default(0);
            $table->decimal('letter', 9,2)->default(0);
            $table->decimal('rent', 9,2)->default(0);
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
        Schema::dropIfExists('equipment_proformas');
    }
}
