<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitsHoursTable extends Migration
{
    public function up()
    {
        Schema::create('permits_hours', function (Blueprint $table) {
            $table->id();
            $table->text('reason')->nullable();
            $table->date('date_start')->nullable();
            $table->unsignedDecimal('hour', 4, 2)->nullable();
            $table->foreignId('worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permits_hours');
    }
}
