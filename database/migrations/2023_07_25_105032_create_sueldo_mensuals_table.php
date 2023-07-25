<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSueldoMensualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sueldo_mensuals', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->string('nameMonth')->nullable();
            $table->string('shortName')->nullable();
            $table->float('total')->nullable();
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
        Schema::dropIfExists('sueldo_mensuals');
    }
}
