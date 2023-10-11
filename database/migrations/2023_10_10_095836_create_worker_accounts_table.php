<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers')->nullOnDelete();
            $table->string('number_account')->nullable();
            $table->enum('currency', ['PEN', 'USD'])->nullable();
            $table->foreignId('bank_id')->nullable()
                ->constrained('banks')->nullOnDelete();
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
        Schema::dropIfExists('worker_accounts');
    }
}
