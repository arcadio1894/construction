<?php
/**
 * Created by PhpStorm.
 * User: Milly
 * Date: 27/05/2024
 * Time: 11:09 AM
 */

namespace App\Services;


use App\TipoCambio;

class TipoCambioService
{
    public function obtenerPorFecha($fecha)
    {
        return TipoCambio::whereDate('fecha', $fecha)->first();
    }

    public function obtenerPorRangoFechas($fechaInicio, $fechaFin)
    {
        return TipoCambio::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }
}