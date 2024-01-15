<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocierAndHesToFinanceWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_works', function (Blueprint $table) {
            $table->enum('docier', ['pending', 'signed'])->nullable()->default(null);
            $table->string('hes')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_works', function (Blueprint $table) {
            //
        });
    }
}
