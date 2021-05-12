<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->require();
            $table->string('RUC')->require()->unique();
            $table->string('code')->require()->unique();
            $table->string('contact_name')->nullable();
            $table->string('adress')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable()->unique();
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
        Schema::dropIfExists('customers');
    }
}
