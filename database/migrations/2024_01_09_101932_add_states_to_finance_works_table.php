<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatesToFinanceWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_works', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `finance_works` CHANGE `state_work` `state_work` ENUM('to_start', 'in_progress', 'finished', 'stopped', 'canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");

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
