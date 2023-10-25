<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatePermitHourToAssistanceDetailsTable extends Migration
{

    public function up()
    {
        Schema::table('assistance_details', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `assistance_details` CHANGE `status` `status` ENUM('A', 'S', 'M', 'J', 'F', 'V', 'P', 'T', 'H', 'L','PH','U') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'A';");
        });
    }

    public function down()
    {
        Schema::table('assistance_details', function (Blueprint $table) {
            //
        });
    }
}
