<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumenQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumen_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('quotes')->nullable();
            $table->string('code')->nullable();
            $table->text('description_quote')->nullable();
            $table->dateTime('date_quote')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->nullable();
            $table->string('customer')->nullable();
            $table->foreignId('contact_id')->constrained('contact_names')->nullable();
            $table->string('contact')->nullable();
            $table->decimal('total_sin_igv', 9,2)->nullable();
            $table->decimal('total_con_igv', 9,2)->nullable();
            $table->decimal('total_utilidad_sin_igv', 9,2)->nullable();
            $table->decimal('total_utilidad_con_igv', 9,2)->nullable();
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
        Schema::dropIfExists('resumen_quotes');
    }
}
