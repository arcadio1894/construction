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
            $table->string('measure')->nullable();
            $table->foreignId('unit_measure_id')->constrained('unit_measures');
            $table->decimal('stock_max', 6,2)->default(0);
            $table->decimal('stock_min', 6,2)->default(0);
            $table->decimal('stock_current', 6,2)->default(0);
            $table->enum('priority', ['Aceptable', 'Agotado', 'Completo', 'Por agotarse']);
            $table->decimal('unit_price', 9,2)->nullable()->default(0);
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories');
            $table->foreignId('material_type_id')->nullable()->constrained('material_types');
            $table->foreignId('subtype_id')->nullable()->constrained('subtypes');
            $table->foreignId('exampler_id')->nullable()->constrained('examplers');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('warrant_id')->nullable()->constrained('warrants');
            $table->foreignId('quality_id')->nullable()->constrained('qualities');
            $table->foreignId('typescrap_id')->nullable()->constrained('typescraps');
            $table->boolean('consumable_default')->nullable()->default(false);
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
