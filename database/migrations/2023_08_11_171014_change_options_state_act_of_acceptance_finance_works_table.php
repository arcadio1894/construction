<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOptionsStateActOfAcceptanceFinanceWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_works', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `finance_works` CHANGE `state_act_of_acceptance` `state_act_of_acceptance` ENUM('pending_signature', 'signed', 'not_signed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
