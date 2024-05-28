<?php

namespace App\Http\Controllers;

use App\Services\TipoCambioService;
use App\TipoCambio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TipoCambioController extends Controller
{
    protected $tipoCambioService;

    public function __construct(TipoCambioService $tipoCambioService)
    {
        $this->tipoCambioService = $tipoCambioService;
    }

    public function guardarTipoCambios()
    {
        DB::beginTransaction();
        try {

            // Ruta al archivo Excel
            $ruta_excel = public_path('/excels/tipoCambios.xlsx');

            // Leer el archivo Excel y obtener los datos
            $datos_excel = Excel::toArray([], $ruta_excel);

            // Obtener la primera hoja del Excel
            $hoja = $datos_excel[0];

            foreach (array_slice($hoja, 1) as $fila) {
                // Convertir la fecha al formato YYYY-MM-DD
                $fecha_celda = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fila[0]))->toDateString();
                $precioCompra = $fila[1];
                $precioVenta = $fila[2];

                $tipoCambio = TipoCambio::create([
                    'fecha' => $fecha_celda ,
                    'precioCompra' => $precioCompra,
                    'precioVenta' => $precioVenta
                ]);

            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Guardado de tipos de cambio con éxito.'], 200);

    }

    public function rellenarTipoCambios()
    {
        $startDate = Carbon::createFromDate(2022, 1, 1);
        $endDate = Carbon::now();

        $contador = 0;
        while ($startDate->lte($endDate)) {
            $exists = TipoCambio::whereDate('fecha', $startDate->toDateString())->exists();

            if (!$exists) {
                // Buscar el registro anterior más cercano
                $registroAnterior = TipoCambio::whereDate('fecha', '<', $startDate->toDateString())
                    ->orderBy('fecha', 'desc')
                    ->first();

                if ($registroAnterior) {
                    $precioCompra = $registroAnterior->precioCompra;
                    $precioVenta = $registroAnterior->precioVenta;
                } else {
                    // Si no hay un registro anterior, define valores predeterminados
                    $precioCompra = 0; // Ajusta esto según sea necesario
                    $precioVenta = 0;  // Ajusta esto según sea necesario
                }

                TipoCambio::create([
                    'fecha' => $startDate->toDateString(),
                    'precioCompra' => $precioCompra,
                    'precioVenta' => $precioVenta
                ]);

                $contador++;
            }

            $startDate->addDay();
        }

        return response()->json(['message' => 'Relleno completo, se relleno '.$contador]);
    }

    public function generarTipoCambios(Request $request)
    {
        // Fecha de inicio
        $fecha_inicio = '2022-01-01';

        // Fecha actual
        $fecha_actual = date('Y-m-d');

        // Array para almacenar los resultados
        $resultados = [];

        // Iterar desde la fecha de inicio hasta la fecha actual
        $fecha_actual_iterar = $fecha_inicio;
        while ($fecha_actual_iterar <= $fecha_actual) {
            // Llamar al método buscarFecha para cada fecha
            $resultado_fecha = $this->buscarFecha($request, $fecha_actual_iterar);

            // Agregar el resultado al array de resultados
            $resultados[] = $resultado_fecha;

            // Incrementar la fecha para la siguiente iteración
            $fecha_actual_iterar = date('Y-m-d', strtotime($fecha_actual_iterar . ' +1 day'));
        }

        // Retornar los resultados
        return response()->json($resultados);
    }

    public function buscarFecha(Request $request, $fecha_buscada)
    {
        // Ruta al archivo Excel
        $ruta_excel = public_path('/excels/tipoCambios.xlsx');

        // Leer el archivo Excel y obtener los datos
        $datos_excel = Excel::toArray([], $ruta_excel);
        //dump($datos_excel);
        // Obtener la primera hoja del Excel
        $hoja = $datos_excel[0];

        //dd($hoja);

        // Fecha buscada en formato YYYY-MM-DD
        //$fecha_buscada = '2022-01-01'; // Cambia la fecha según el formato de tu archivo

        // Buscar la fecha en el Excel
        $fecha_encontrada = null;
        $precioCompra = null;
        $precioVenta = null;

        foreach (array_slice($hoja, 1) as $fila) {
            $fecha_celda = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fila[0]))->toDateString();
            // Convertir la fecha al formato YYYY-MM-DD
            //$fecha_celda = date('Y-m-d', strtotime($fila['fecha']));

            // Verificar si la fecha coincide con la fecha buscada
            dump($fecha_celda);
            dump($fecha_buscada);
            if ($fecha_celda == $fecha_buscada) {
                // Si la fecha coincide, obtener los precios de compra y venta
                $precioCompra = $fila[1];
                $precioVenta = $fila[0];

                // Guardar la fecha encontrada y salir del bucle
                $fecha_encontrada = $fecha_buscada;
                dump($fecha_encontrada);
                break;

            }
        }

        // Verificar si se encontró la fecha
        if ($fecha_encontrada !== null) {
            dd($fecha_encontrada);
            return response()->json([
                'status' => true,
                'fecha' => $fecha_encontrada,
                'precioCompra' => $precioCompra,
                'precioVenta' => $precioVenta,
            ]);
        } else {
            dd($fecha_buscada);
            return response()->json([
                'status' => false,
                'mensaje' => "No se encontraron precios para la fecha $fecha_buscada",
            ]);
        }
    }

    public function obtenerTipoCambio()
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

        dump($response);

        $tipoCambioSbs = json_decode(json_encode($response));
        dd();
        return [
            'precioCompra' => $tipoCambioSbs->precioCompra,  // Ejemplo de precio de compra
            'precioVenta' => $tipoCambioSbs->precioVenta    // Ejemplo de precio de venta
        ];
    }

    public function mostrarTipoCambio($fecha)
    {
        $tipoCambio = $this->tipoCambioService->obtenerPorFecha($fecha);
        return response()->json($tipoCambio);
    }

    public function mostrarTipoCambioActual()
    {
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');
        $tipoCambio = $this->tipoCambioService->obtenerPorFecha($fecha);
        return response()->json($tipoCambio);
    }

    public function mostrarTipoCambioPrueba()
    {
        $fechaInicio = "2024-05-20";
        $fechaFin = "2024-05-27";
        //$tipoCambio = $this->tipoCambioService->obtenerPorMonthYear(5, 2024);
        $tipoCambio = $this->tipoCambioService->obtenerPorFecha($fechaFin);
        return $tipoCambio->precioCompra;
    }

    public function mostrarTipoCambioRango($fechaInicio, $fechaFin)
    {
        $tipoCambio = $this->tipoCambioService->obtenerPorRangoFechas($fechaInicio, $fechaFin);
        return response()->json($tipoCambio);
    }

}
