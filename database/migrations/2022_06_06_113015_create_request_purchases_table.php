<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained('quote');
            $table->enum('urgency', ['high', 'medium', 'low'])->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::dropIfExists('request_purchases');
    }
}
