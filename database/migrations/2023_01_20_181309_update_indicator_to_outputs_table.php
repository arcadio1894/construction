<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicatorToOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outputs', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `outputs` CHANGE `indicator` `indicator` ENUM('or', 'orn', 'ore', 'ors') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outputs', function (Blueprint $table) {
            //
        });
    }
}
