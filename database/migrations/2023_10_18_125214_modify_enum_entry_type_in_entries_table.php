<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEnumEntryTypeInEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            DB::statement("ALTER TABLE `entries` 
            MODIFY COLUMN `entry_type` ENUM('Por compra', 'Retacería', 'Inventario') 
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
            NULL DEFAULT 'Por compra';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            //
        });
    }
}
