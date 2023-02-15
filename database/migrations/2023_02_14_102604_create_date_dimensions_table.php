<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_dimensions', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedInteger('day');
            $table->unsignedInteger('month');
            $table->unsignedInteger('year');
            $table->string('day_name');
            $table->string('day_suffix', 2);
            $table->unsignedInteger('day_of_week');
            $table->unsignedInteger('day_of_year');
            $table->unsignedInteger('is_weekend');
            $table->unsignedInteger('week');
            $table->unsignedInteger('week_of_month');
            $table->unsignedInteger('week_of_year');
            $table->string('month_name');
            $table->string('month_year');
            $table->string('month_name_year');
            $table->unsignedInteger('quarter');
            $table->string('quarter_name');
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
        Schema::dropIfExists('date_dimensions');
    }
}
