<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_service_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_service_id')->constrained('order_services');
            $table->string('service')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity',9,2)->default(0);
            $table->decimal('price',9,2)->default(0);
            $table->decimal('igv',9,2)->default(0);
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
        Schema::dropIfExists('order_service_details');
    }
}
