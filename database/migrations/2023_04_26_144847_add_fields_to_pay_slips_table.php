<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPaySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_slips', function (Blueprint $table) {
            $table->mediumText('empresa')->nullable();
            $table->text('ruc')->nullable();
            $table->integer('codigo')->nullable();
            $table->mediumText('nombre')->nullable();
            $table->string('cargo')->nullable();
            $table->integer('semana')->nullable();
            $table->mediumText('fecha')->nullable();
            $table->float('pagoxdia')->nullable();
            $table->float('pagoXHora')->nullable();
            $table->float('diasTrabajados')->nullable();
            $table->float('asignacionFamiliarDiaria')->nullable();
            $table->float('asignacionFamiliarSemanal')->nullable();
            $table->float('horasOrdinarias')->nullable();
            $table->float('montoHorasOrdinarias')->nullable();
            $table->float('horasAl25')->nullable();
            $table->float('montoHorasAl25')->nullable();
            $table->float('horasAl35')->nullable();
            $table->float('montoHorasAl35')->nullable();
            $table->float('horasAl100')->nullable();
            $table->float('montoHorasAl100')->nullable();
            $table->float('dominical')->nullable();
            $table->float('montoDominical')->nullable();
            $table->float('vacaciones')->nullable();
            $table->float('montoVacaciones')->nullable();
            $table->float('reintegro')->nullable();
            $table->float('gratificaciones')->nullable();
            $table->float('totalIngresos')->nullable();
            $table->string('sistemaPension')->nullable();
            $table->float('montoSistemaPension')->nullable();
            $table->float('rentaQuintaCat')->nullable();
            $table->float('pensionDeAlimentos')->nullable();
            $table->float('prestamo')->nullable();
            $table->float('otros')->nullable();
            $table->float('totalDescuentos')->nullable();
            $table->float('essalud')->nullable();
            $table->float('totalNetoPagar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pay_slips', function (Blueprint $table) {
            //
        });
    }
}
