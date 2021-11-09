<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('ordered_quantity',9,2)->default(0);
            $table->decimal('entered_quantity',9,2)->default(0);
            $table->boolean('isComplete')->default(true);
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
        Schema::dropIfExists('detail_entries');
    }
}
