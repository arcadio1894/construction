<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadStockFilesRequest;
use App\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UploadFilesController extends Controller
{
    public function showUploadFilesStocksMaterials()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('files.stockFiles', compact( 'permissions'));
    }

    public function downloadExampleStockFile()
    {
        $filePath = public_path('/excels/ejemploExcelMinimoMaximo.xlsx');
        return response()->download($filePath);
    }

    public function uploadFilesStocksMaterials(UploadStockFilesRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            if ($request->file('file')->isValid()) {
                $file = $request->file('file');
                $filePath = $file->storeAs('public/excels', $file->getClientOriginalName());
                $publicPath = public_path('excels/' . $file->getClientOriginalName());

                // Leer el archivo Excel
                $datos_excel = Excel::toArray([], $publicPath);

                // Obtener la primera hoja del Excel
                $hoja = $datos_excel[0];

                // Recorrer las filas del archivo Excel
                foreach (array_slice($hoja, 1) as $fila) {
                    $materialCode = $fila[0];
                    $stockMin = $fila[1];
                    $stockMax = $fila[2];

                    // Buscar el material por código y actualizar los valores de stock_min y stock_max
                    if (!empty($materialCode) && is_numeric($stockMin) && is_numeric($stockMax)) {
                        // Buscar el material por código y actualizar los valores de stock_min y stock_max
                        $material = Material::where('code', $materialCode)->first();
                        if ($material) {
                            $material->stock_min = $stockMin;
                            $material->stock_max = $stockMax;
                            $material->save();
                        }
                    }
                }
                // Confirmar la transacción
                DB::commit();

                return response()->json(['message' => 'Archivo subido exitosamente.'], 200);
            } else {
                throw new \Exception('Hubo un problema al subir el archivo');
            }
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }
}
