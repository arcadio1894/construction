<?php

namespace App\Http\Controllers;

use App\Area;
use App\Exports\DatabaseMaterialsByAnaquelExport;
use App\Http\Requests\DeleteShelfRequest;
use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Item;
use App\Location;
use App\Material;
use App\Shelf;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShelfController extends Controller
{
    public function index($warehouse, $area)
    {
        $area = Area::find($area);
        $warehouse = Warehouse::find($warehouse);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        //dd($area);
        return view('inventory.shelves', compact('area', 'warehouse', 'permissions'));
    }

    public function create()
    {
        //
    }

    public function store(StoreShelfRequest $request)
    {
        $validated = $request->validated();

        $shelf = Shelf::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'warehouse_id' => $request->get('warehouse_id'),
        ]);

        return response()->json(['message' => 'Anaquel guardado con éxito.'], 200);

    }

    public function show(Shelf $shelf)
    {
        //
    }

    public function edit(Shelf $shelf)
    {
        //
    }

    public function update(UpdateShelfRequest $request)
    {
        $validated = $request->validated();

        $shelf = Shelf::find($request->get('shelf_id'));

        $shelf->name = $request->get('name');
        $shelf->comment = $request->get('comment');

        $shelf->save();

        return response()->json(['message' => 'Anaquel modificado con éxito.'], 200);

    }

    public function destroy(DeleteShelfRequest $request)
    {
        $validated = $request->validated();

        $shelf = Shelf::find($request->get('shelf_id'));

        $shelf->delete();

        return response()->json(['message' => 'Anaquel eliminado con éxito.'], 200);

    }

    public function getShelves( $id_warehouse )
    {
        $shelves = Shelf::where('warehouse_id', $id_warehouse)->with('warehouse')->get();

        //dd(datatables($shelves)->toJson());
        return datatables($shelves)->toJson();
    }

    public function exportMaterialsAnaquel()
    {
        $shelf_id = $_GET['shelf'];
        $shelf = Shelf::find($shelf_id);
        $warehouse = Warehouse::find($shelf->warehouse_id);
        $locations = Location::where('warehouse_id', $shelf->warehouse_id)
            ->where('shelf_id', $shelf_id)
            ->pluck('id')->toArray();
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('description', 'not like', '%EDESCE%')
            ->where('enable_status', 1)
            ->get();

        $materials_array = [];

        foreach ( $materials as $material )
        {
            $priority = '';
            if ( $material->stock_current > $material->stock_max ){
                $priority = 'Completo';
            } else if ( $material->stock_current = $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current > $material->stock_min && $material->stock_current < $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current = $material->stock_min ){
                $priority = 'Por agotarse';
            } else if ( $material->stock_current < $material->stock_min || $material->stock_current == 0 ){
                $priority = 'Agotado';
            }

            $itemsCount = Item::where('material_id', $material->id)
                ->whereIn('location_id', $locations)
                ->where('state_item', '<>', 'exited')
                ->sum('percentage');

            if ( $itemsCount > 0 )
            {
                array_push($materials_array, [
                    'code' => $material->code,
                    'material' => $material->full_description,
                    'measure' => $material->measure,
                    'unit' => ($material->unitMeasure == null) ? '':$material->unitMeasure->name,
                    'stock_max' => $material->stock_max,
                    'stock_min' => $material->stock_min,
                    'stock_current' => $itemsCount,
                    'priority'=> $priority,
                    'price'=> $material->unit_price,
                    'category'=> ($material->category == null) ? '': $material->category->name,
                    'subcategory'=> ($material->subcategory == null) ? '': $material->subcategory->name,
                    'type'=> ($material->materialType == null) ? '': $material->materialType->name,
                    'subtype'=> ($material->subType == null) ? '': $material->subType->name,
                    'brand'=> ($material->brand == null) ? '': $material->brand->name,
                    'exampler'=> ($material->exampler == null) ? '': $material->exampler->name,
                    'quality'=> ($material->quality == null) ? '': $material->quality->name,
                    'warrant'=> ($material->warrant == null) ? '':$material->warrant->name,
                    'scrap'=> ($material->typeScrap == null) ? '':$material->typeScrap->name,
                ]);

            }

        }
        //dump($materials_array);
        $title = '';
        if ( !is_null($shelf) )
        {
            $title = 'BASE DE MATERIALES EN EL ANAQUEL: ' . $shelf->name . ' DEL ALMACEN '.$warehouse->name;
        }

        return Excel::download(new DatabaseMaterialsByAnaquelExport($materials_array, $title), 'reporte_base_materiales_por_anaquel.xlsx');


    }
}
