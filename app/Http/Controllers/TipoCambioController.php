<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TipoCambioController extends Controller
{
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


}
