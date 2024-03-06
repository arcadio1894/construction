<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumenEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumen_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resumen_quote_id')->constrained('resumen_quotes');
            $table->foreignId('equipment_id')->constrained('equipments')->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_materials', 9, 2)->nullable();
            $table->decimal('total_consumables', 9, 2)->nullable();
            $table->decimal('total_workforces', 9, 2)->nullable();
            $table->decimal('total_turnstiles', 9, 2)->nullable();
            $table->decimal('total_workdays', 9, 2)->nullable();
            $table->decimal('quantity', 9, 2)->nullable();
            $table->decimal('total', 9, 2)->nullable();
            $table->decimal('utility', 9, 2)->nullable();
            $table->decimal('letter', 9, 2)->nullable();
            $table->decimal('rent', 9, 2)->nullable();
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
        Schema::dropIfExists('resumen_equipments');
    }
}
