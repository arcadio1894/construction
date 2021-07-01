<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('description');
            $table->string('measure');
            $table->string('unit_measure');
            $table->decimal('stock_max', 6,2);
            $table->decimal('stock_min', 6,2);
            $table->decimal('stock_current', 6,2);
            $table->enum('priority', ['Aceptable', 'Agotado', 'Completo', 'Por agotarse']);
            $table->decimal('unit_price', 9,2)->default(0);
            $table->string('image')->nullable();
            $table->foreignId('material_type_id')->constrained('material_types');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('exampler_id')->constrained('examplers');
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('serie')->nullable();
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
        Schema::dropIfExists('materials');
    }
}
