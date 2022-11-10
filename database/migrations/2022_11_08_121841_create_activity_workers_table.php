<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->nullable()
                ->constrained('activities');
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers');
            $table->float('hours_plan')->nullable();
            $table->float('hours_real')->nullable();
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
        Schema::dropIfExists('activity_workers');
    }
}
