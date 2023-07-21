<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
    'projection_id',
    'worker_id',
    'salary'
     * @return void
     */
    public function up()
    {
        Schema::create('projection_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projection_id')->nullable()->constrained('projections');
            $table->foreignId('worker_id')->nullable()->constrained('workers');
            $table->decimal('salary', 12, 2)->nullable();
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
        Schema::dropIfExists('projection_details');
    }
}
