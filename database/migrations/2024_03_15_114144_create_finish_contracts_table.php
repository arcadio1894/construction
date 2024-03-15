<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finish_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->nullable()->constrained('workers');
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->date('date_finish')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('active')->nullable()->default(1);
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
        Schema::dropIfExists('finish_contracts');
    }
}
