<?php

namespace App\Http\Controllers;

use App\DateDimension;
use App\Projection;
use App\ProjectionDetail;
use App\Services\TipoCambioService;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectionController extends Controller
{
    protected $tipoCambioService;

    public function __construct(TipoCambioService $tipoCambioService)
    {
        $this->tipoCambioService = $tipoCambioService;
    }

    public function createProjections()
    {
        $dateCurrent = Carbon::now('America/Lima');

        $workers = Worker::where('enable', 1)->where('id', '<>', 1)->get();

        //$typeExchange = $this->getExchange($dateCurrent->format('Y-m-d'));
        $typeExchange = $this->obtenerTipoCambio($dateCurrent->format('Y-m-d'));

        $quantityDays = $dateCurrent->daysInMonth;

        DB::beginTransaction();
        try {
            $projection = Projection::create([
                'year' => $dateCurrent->year,
                'month' => $dateCurrent->month,
                'projection_month_soles' => 0,
                'projection_month_dollars' => 0,
                'projection_week_soles' => 0,
                'projection_week_dollars' => 0
            ]);

            $projection_month_soles = 0;
            $projection_month_dollars = 0;

            foreach ( $workers as $worker )
            {
                $detail = ProjectionDetail::create([
                    'projection_id' => $projection->id,
                    'worker_id' => $worker->id,
                    'salary' => ($worker->monthly_salary == null) ? 0:$worker->monthly_salary
                ]);

                $projection_month_soles += (($worker->monthly_salary == null) ? 0:$worker->monthly_salary);
                $projection_month_dollars += ((($worker->monthly_salary == null) ? 0:$worker->monthly_salary) / $typeExchange->precioCompra);

            }
            $projection_week_soles = $projection_month_soles/($quantityDays/7);
            $projection_week_dollars = $projection_month_dollars/($quantityDays/7);

            $projection->projection_month_soles = $projection_month_soles;
            $projection->projection_month_dollars = $projection_month_dollars;
            $projection->projection_week_soles = $projection_week_soles;
            $projection->projection_week_dollars = $projection_week_dollars;
            $projection->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Proyección guardada con exito'], 200);

    }

    public function getExchange($fecha)
    {
        //dump($fecha);
        $date = Carbon::createFromFormat('Y-m-d', $fecha);
        //dump($date);
        $dateCurrent = Carbon::now('America/Lima');
        //dump($dateCurrent);

        //$token = 'apis-token-8651.OrHQT9azFQteF-IhmcLXP0W2MkemnPNX';
        $token = env('TOKEN_DOLLAR');
        //$curl = curl_init();

        /*curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));*/
        /*curl_setopt_array($curl, array(
            // para usar la api versión 2
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sbs/tipo-cambio?date=' . $fecha,
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

        if ($response === false) {
            // Error en la ejecución de cURL
            $errorNo = curl_errno($curl);
            $errorMsg = curl_error($curl);
            curl_close($curl);

            // Manejar el error
            //echo "cURL Error #{$errorNo}: {$errorMsg}";
        } else {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode >= 200 && $httpCode < 300) {
                // La solicitud fue exitosa
                $data = json_decode($response, true);
                // Manejar la respuesta exitosa
                //echo "Respuesta exitosa: ";
                //print_r($data);
            } else {
                // La solicitud no fue exitosa
                // Decodificar el mensaje de error si la respuesta está en formato JSON
                $errorData = json_decode($response, true);
                //echo "Error en la solicitud: ";
                ///print_r($errorData);
                $response = [
                    "precioCompra"=> 3.738,
                    "precioVenta"=> 3.746,
                    "moneda"=> "USD",
                    "fecha"=> "2024-05-24"
                ];
            }
        }*/

        //curl_close($curl);

        //$tipoCambioSunat = json_decode($response);
        //$responseObject = json_decode(json_encode($response));
        //return $responseObject;

    }

    public function obtenerTipoCambio($fechaFormato)
    {
        $tipoCambio = $this->tipoCambioService->obtenerPorFecha($fechaFormato);
        return $tipoCambio;
    }
}
