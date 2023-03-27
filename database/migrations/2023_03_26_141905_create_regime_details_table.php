<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegimeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regime_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regime_id')->nullable()
                ->constrained('regimes')->nullOnDelete();
            $table->integer('dayNumber')->nullable();
            $table->string('dayName')->nullable();
            $table->foreignId('working_day_id')->nullable()
                ->constrained('working_days')->nullOnDelete();
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
        Schema::dropIfExists('regime_details');
    }
}
