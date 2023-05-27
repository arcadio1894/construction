<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdelantoNewStatesToSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            $table->enum('state_pay', ['pending', 'pending50','canceled'])->default('pending');
            $table->decimal('advance', '12', 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_credits', function (Blueprint $table) {
            //
        });
    }
}
