<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('personal_address')->nullable();
            $table->date('birthplace')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('level_school')->nullable();
            $table->string('image')->nullable();
            $table->string('dni')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->integer('num_children')->nullable();
            $table->decimal('daily_salary', 9, 2)->nullable();
            $table->decimal('monthly_salary', 9, 2)->nullable();
            $table->enum('gender', ['f', 'm'])->nullable();
            $table->float('assign_family')->nullable();
            $table->float('essalud')->nullable();
            $table->float('five_category')->nullable();
            $table->text('observation')->nullable();
            $table->foreignId('user_id')->nullable()
                ->constrained('users');
            $table->foreignId('contract_id')->nullable()
                ->constrained('contracts');
            $table->foreignId('civil_status_id')->nullable()
                ->constrained('civil_statuses');
            $table->foreignId('work_function_id')->nullable()
                ->constrained('work_functions');
            $table->foreignId('pension_system_id')->nullable()
                ->constrained('pension_systems');
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
        Schema::dropIfExists('workers');
    }
}
