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
                ->constrained('areas');
            $table->foreignId('warehouse_id')
                ->constrained('warehouses');
            $table->foreignId('shelf_id')
                ->constrained('shelves');
            $table->foreignId('level_id')
                ->constrained('levels');
            $table->foreignId('container_id')
                ->constrained('containers');
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
