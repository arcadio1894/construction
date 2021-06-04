<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_entry_id')->constrained('detail_entries');
            $table->foreignId('material_id')->constrained('materials');
            $table->string('code')->nullable();
            $table->decimal('length', 9,2)->nullable();
            $table->decimal('width', 9,2)->nullable();
            $table->decimal('weight',9,2)->nullable();
            $table->decimal('price',9,2)->nullable();
            $table->decimal('percentage',9,2)->nullable();
            $table->foreignId('material_type_id')->constrained('material_types');
            $table->foreignId('location_id')->constrained('locations');
            $table->enum('state', ['good', 'bad'])->nullable();
            $table->enum('state_item', ['entered', 'scraped', 'reserved', 'exited'])->nullable();
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
        Schema::dropIfExists('items');
    }
}
