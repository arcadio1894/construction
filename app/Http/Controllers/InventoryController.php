<?php

namespace App\Http\Controllers;

use App\Exports\InventoryMaterialsExport;
use App\Item;
use App\Location;
use App\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function getDataInventory(Request $request, $pageNumber = 1)
    {
        $perPage = 20;
        $full_name = $request->input('full_name');

        $query = Material::where('enable_status', 1)
            ->orderBy('id');

        if ($full_name != "") {
            // Convertir la cadena de búsqueda en un array de palabras clave
            $keywords = explode(' ', $full_name);

            // Construir la consulta para buscar todas las palabras clave en el campo full_name
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('full_name', 'LIKE', '%' . $keyword . '%');
                }
            });

            // Asegurarse de que todas las palabras clave estén presentes en la descripción
            foreach ($keywords as $keyword) {
                $query->where('full_name', 'LIKE', '%' . $keyword . '%');
            }
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $materials = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $materials as $material )
        {
            $localizacion = $this->getLocationsGeneralMaterial($material->id);

            array_push($array, [
                "id" => $material->id,
                "code" => $material->code,
                "full_name" => $material->full_name,
                "stock" => $material->stock_current,
                "inventory" => $material->inventory,
                "location" => $localizacion
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function getLocationsGeneralMaterial($material)
    {
        $textLocations = "";
        $items = Item::where('material_id', $material)
            ->where('state_item', '<>', 'exited')
            ->get();

        $locations = $items->pluck('location_id')->unique()->toArray();

        if (!empty($locations)) {
            // No se encontraron items para el material específico
            foreach ($locations as $location) {
                $ubicacion = Location::with(['shelf', 'level'])->find($location);

                $textLocations = $textLocations . $ubicacion->shelf->name ." - ". $ubicacion->level->name ." | ";

            }

        }

        return $textLocations;
    }

    public function listInventory()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('inventory.list', compact( 'permissions'));

    }

    public function saveListInventory(Request $request)
    {
        DB::beginTransaction();
        try {

            $inventories = $request->input('data');

            foreach ( $inventories as $inventory )
            {
                //$inventory['description']
                $material = Material::find($inventory['material_id']);
                if ( isset($material) )
                {
                    $material->inventory = (float)$inventory['quantity'];
                    $material->save();
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Datos guardados con éxito.'], 200);

    }

    public function exportListInventory()
    {
        $materials = Material::where('description', 'not like', '%EDESCE%')
            ->where('enable_status', 1)
            ->get();

        $materials_array = [];

        foreach ( $materials as $material )
        {
            $localizacion = $this->getLocationsGeneralMaterial($material->id);

            array_push($materials_array, [
                "id" => $material->id,
                "code" => $material->code,
                "material" => $material->full_name,
                "stock_current" => $material->stock_current,
                "inventory" => $material->inventory,
                "location" => $localizacion
            ]);
        }
        //dump($materials_array);

        $title = 'BASE DE MATERIALES COMPLETA';

        return Excel::download(new InventoryMaterialsExport($materials_array, $title), 'reporte_inventario_materiales.xlsx');
    }

}
