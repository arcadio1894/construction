<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInventoryNullableToMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Necesitas tener instalado doctrine/dbal para usar change()
            $table->decimal('inventory', 15, 2)
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->decimal('inventory', 15, 2)
                ->default(0)
                ->nullable(false)
                ->change();
        });
    }
}
