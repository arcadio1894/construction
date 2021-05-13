<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments');
            $table->foreignId('material_id')->constrained('materials');
            $table->string('extra')->nullable();
            $table->decimal('quantity', 9,2)->nullable();
            $table->string('unit_measure')->nullable();
            $table->decimal('unit_price', 9,2)->nullable();
            $table->decimal('total_price', 9,2)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('default_items');
    }
}
