<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained('quotes');
            $table->date('raise_date')->nullable();
            $table->enum('act_of_acceptance', ['generate', 'not_generate', 'pending'])->nullable();
            $table->enum('state_act_of_acceptance', ['pending_signature', 'signed'])->nullable();
            $table->enum('advancement', ['y', 'n'])->nullable();
            $table->decimal('amount_advancement', 15,2)->nullable()->default(0);
            $table->enum('detraction', ['oc', 'os'])->nullable();
            $table->enum('invoiced', ['y', 'n'])->nullable();
            $table->string('number_invoice')->nullable();
            $table->integer('month_invoice')->nullable();
            $table->date('date_issue')->nullable();
            $table->date('date_admission')->nullable();
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->enum('state', ['pending','canceled'])->nullable()->default('pending');
            $table->date('date_paid')->nullable();
            $table->longText('observation')->nullable();
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
        Schema::dropIfExists('finance_works');
    }
}
