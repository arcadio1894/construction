<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFifthCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fifth_categories', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->float('amount')->nullable();
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers')->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fifth_categories');
    }
}
