<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlimoniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alimonies', function (Blueprint $table) {
            $table->id();
            $table->integer('week')->nullable();
            $table->integer('month')->nullable();
            $table->date('date')->nullable();
            $table->float('amount')->nullable();
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers')->nullOnDelete();
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
        Schema::dropIfExists('alimonies');
    }
}
