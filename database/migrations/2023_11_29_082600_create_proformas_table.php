<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->text('description_quote')->nullable();
            $table->dateTime('date_quote')->nullable();
            $table->dateTime('date_validate')->nullable();
            $table->text('way_to_pay')->nullable();
            $table->text('delivery_time')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contact_names')->nullOnDelete();
            $table->foreignId('payment_deadline_id')->nullable()->constrained('payment_deadlines')->nullOnDelete();
            $table->boolean('vb_proforma')->nullable();
            $table->date('date_vb_proforma')->nullable();
            $table->foreignId('user_vb_proforma')->constrained('users')->nullOnDelete();
            $table->decimal('total', 9,2)->default(0);
            $table->enum('state', ['created', 'confirmed', 'destroy', 'expired'])->nullable()->default('created');
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
        Schema::dropIfExists('proformas');
    }
}
