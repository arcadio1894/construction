<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_purchase_id')->nullable()->constrained('request_purchases');
            $table->foreignId('material_id')
                ->nullable()->constrained('materials')
                ->nullOnDelete();
            $table->float('quantity')->nullable();
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
        Schema::dropIfExists('request_purchase_details');
    }
}
