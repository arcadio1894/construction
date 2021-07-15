<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('referral_guide')->nullable();
            $table->string('purchase_order')->nullable();
            $table->string('invoice')->nullable();
            $table->foreignId('supplier_id')->nullable()
                ->constrained('suppliers');
            $table->enum('entry_type', ['Por compra', 'Retacería'])->default('Por compra');
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
        Schema::dropIfExists('entries');
    }
}
