<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('entry_id')->nullable()->constrained('entries');
            $table->string('invoice')->nullable();
            $table->string('image_invoice')->nullable();
            $table->string('purchase_order')->nullable();
            $table->decimal('total_soles', '12', 2)->nullable()->default(0);
            $table->decimal('total_dollars', '12', 2)->nullable()->default(0);
            $table->dateTime('date_issue')->nullable();
            $table->dateTime('date_expiration')->nullable();
            $table->integer('payment_deadline')->nullable();
            $table->enum('state', ['expired', 'by_expire'])->nullable();
            $table->integer('days_to_expiration')->nullable();
            $table->text('observation')->nullable();
            $table->enum('state_credit', ['outstanding', 'canceled']);
            $table->text('observation_extra')->nullable();
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
        Schema::dropIfExists('credits');
    }
}
