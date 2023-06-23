<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_bonuses', function (Blueprint $table) {
            $table->id();
            $table->text('reason')->nullable();
            $table->date('date')->nullable();
            $table->integer('week')->nullable();
            $table->float('amount')->nullable()->default(0);
            $table->foreignId('worker_id')->nullable()
                ->constrained('workers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_bonuses');
    }
}
