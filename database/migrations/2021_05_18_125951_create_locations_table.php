<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')
                ->constrained('areas')
                ->onDelete('cascade');;
            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->onDelete('cascade');;
            $table->foreignId('shelf_id')
                ->constrained('shelves')
                ->onDelete('cascade');;
            $table->foreignId('level_id')
                ->constrained('levels')
                ->onDelete('cascade');;
            $table->foreignId('container_id')
                ->constrained('containers')
                ->onDelete('cascade');;
            $table->string('description');
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
        Schema::dropIfExists('locations');
    }
}
