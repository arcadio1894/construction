<?php

namespace App\Console\Commands;

use App\Audit;
use App\TipoCambio;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class RegistrarTipoCambio extends Command
{
    protected $signature = 'tipocambio:registrar';
    protected $description = 'Obtener el tipo de cambio actual y registrarlo en la base de datos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lógica para obtener el tipo de cambio
        // Aquí deberías llamar al método que ya tienes implementado para obtener el tipo de cambio
        // Ejemplo:
        $fecha = Carbon::now('America/Lima');

        $exists = TipoCambio::whereDate('fecha', $fecha)->exists();

        if (!$exists) {
            $tipoCambio = json_decode($this->obtenerTipoCambio());

            //dd($tipoCambio->precioCompra);

            // Crear el nuevo registro en la base de datos
            TipoCambio::create([
                'fecha' => $fecha,
                'precioCompra' => $tipoCambio->precioCompra,
                'precioVenta' => $tipoCambio->precioVenta,
            ]);

            Audit::create([
                'user_id' => 1,
                'action' => 'Guardar tipoCambio. '.$tipoCambio->precioCompra.' '.$tipoCambio->precioVenta,
                'time' => 0
            ]);
            $this->info("Tipo de cambio registrado para la fecha $fecha. ".$tipoCambio->precioVenta);
        }

        $this->info("Tipo de cambio no registrado para la fecha $fecha. ");
    }

    // Método simulado para obtener el tipo de cambio
    // Debes reemplazar esto con la implementación real
    private function obtenerTipoCambio()
    {
        // Aquí deberías poner la lógica real para obtener el tipo de cambio
        // Por ejemplo, hacer una solicitud HTTP a un API que proporcione el tipo de cambio
        $token = env('TOKEN_DOLLAR');
        $fecha = Carbon::now('America/Lima');
        $fechaFormateada = $fecha->format('Y-m-d');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            // para usar la api versión 2
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sbs/tipo-cambio?date=' . $fechaFormateada,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/api-tipo-cambio-sbs.html',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        $tipoCambioSbs = $response;

        return $tipoCambioSbs;
    }
}
