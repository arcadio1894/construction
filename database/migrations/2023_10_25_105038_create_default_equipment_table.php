<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_equipments', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->decimal('large', 6, 2)->nullable();
            $table->decimal('width', 6, 2)->nullable();
            $table->decimal('high', 6, 2)->nullable();
            $table->foreignId('category_equipment_id')->nullable()->constrained('category_equipments');
            $table->text('details')->nullable();
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
        Schema::dropIfExists('default_equipment');
    }
}
