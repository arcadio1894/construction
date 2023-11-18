<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFifthCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fifth_categories', function (Blueprint $table) {
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fifth_categories', function (Blueprint $table) {
            $table->dropColumn(['year', 'month', 'total_amount']);
        });
    }
}
