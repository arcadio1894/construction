<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timeline_id')->nullable()
                ->constrained('timelines');
            $table->foreignId('work_id')->nullable()
                ->constrained('works');
            $table->foreignId('phase_id')->nullable()
                ->constrained('phases');
            $table->foreignId('quote_id')->nullable()
                ->constrained('quotes');
            $table->foreignId('performer_id')->nullable()
                ->constrained('workers');
            $table->boolean('assign_status')->nullable()->default(false);
            $table->foreignId('parent_task_id')->nullable()
                ->constrained('tasks');
            $table->text('activity')->nullable();
            $table->integer('progress')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('tasks');
    }
}
