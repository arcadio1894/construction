<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description_quote');
            $table->dateTime('date_quote');
            $table->dateTime('date_validate');
            $table->text('way_to_pay');
            $table->text('delivery_time');
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('total', 9,2)->default(0);
            $table->enum('state', ['created', 'confirmed', 'canceled', 'expired']);
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
        Schema::dropIfExists('quotes');
    }
}
