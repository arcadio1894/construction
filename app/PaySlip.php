<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    protected $fillable = [
        'empresa',
        'ruc',
        'codigo',
        'nombre',
        'cargo',
        'semana',
        'fecha',
        'pagoxdia',
        'pagoXHora',
        'diasTrabajados',
        'asignacionFamiliarDiaria',
        'asignacionFamiliarSemanal',
        'horasOrdinarias',
        'montoHorasOrdinarias',
        'horasAl25',
        'montoHorasAl25',
        'horasAl35',
        'montoHorasAl35',
        'horasAl100',
        'montoHorasAl100',
        'dominical',
        'montoDominical',
        'vacaciones',
        'montoVacaciones',
        'reintegro',
        'gratificaciones',
        'totalIngresos',
        'sistemaPension',
        'montoSistemaPension',
        'rentaQuintaCat',
        'pensionDeAlimentos',
        'prestamo',
        'otros',
        'totalDescuentos',
        'essalud',
        'totalNetoPagar'
    ];
}
