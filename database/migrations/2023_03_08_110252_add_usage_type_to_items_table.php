<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsageTypeToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('type')->nullable()->default(false);
            $table->enum('usage', ['new', 'in_use', 'out_of_order', 'finished'])->nullable()->default('new');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
