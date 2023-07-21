<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
    'year',
    'month',
    'projection_month_soles',
    'projection_month_dollars',
    'difference_soles',
    'difference_dollars',
    'projection_week_soles',
    'projection_week_dollars'
     * @return void
     */
    public function up()
    {
        Schema::create('projections', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->decimal('projection_month_soles', 12, 2)->nullable();
            $table->decimal('projection_month_dollars', 12, 2)->nullable();
            $table->decimal('difference_soles', 12, 2)->nullable();
            $table->decimal('difference_dollars', 12, 2)->nullable();
            $table->decimal('projection_week_soles', 12, 2)->nullable();
            $table->decimal('projection_week_dollars', 12, 2)->nullable();
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
        Schema::dropIfExists('projections');
    }
}
