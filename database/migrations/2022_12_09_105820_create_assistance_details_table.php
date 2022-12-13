<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistance_details', function (Blueprint $table) {
            $table->id();
            $table->date('date_assistance')->nullable();
            $table->time('hour_entry')->nullable();
            $table->time('hour_out')->nullable();
            $table->enum('status', ['A', 'S', 'DM', 'FJ', 'F', 'V'])->nullable();
            $table->boolean('justification')->nullable();
            $table->text('obs_justification')->nullable();
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers');
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
        Schema::dropIfExists('assistance_details');
    }
}
