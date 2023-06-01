<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_generals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('valueText')->nullable()->default("");
            $table->float('valueNumber')->nullable()->default(0);
            $table->string('module')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('data_generals');
    }
}
