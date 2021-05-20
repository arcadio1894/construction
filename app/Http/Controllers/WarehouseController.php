<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Requests\DeleteWarehouseRequest;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index($area)
    {
        $area = Area::find($area);
        //dd($area);
        return view('inventory.warehouses', compact('area'));
    }

    public function create()
    {
        //
    }

    public function store(StoreWarehouseRequest $request)
    {
        $validated = $request->validated();

        $warehouse = Warehouse::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'area_id' => $request->get('area_id'),
        ]);

        return response()->json(['message' => 'Almacén guardado con éxito.'], 200);

    }

    public function show(Warehouse $warehouse)
    {
        //
    }

    public function edit(Warehouse $warehouse)
    {
        //
    }

    public function update(UpdateWarehouseRequest $request)
    {
        $validated = $request->validated();

        $warehouse = Warehouse::find($request->get('warehouse_id'));

        $warehouse->name = $request->get('name');
        $warehouse->comment = $request->get('comment');

        $warehouse->save();

        return response()->json(['message' => 'Almacén modificado con éxito.'], 200);

    }

    public function destroy(DeleteWarehouseRequest $request)
    {
        $validated = $request->validated();

        $warehouse = Warehouse::find($request->get('warehouse_id'));

        $warehouse->delete();

        return response()->json(['message' => 'Almacén eliminado con éxito.'], 200);

    }

    public function getWarehouses( $id_area )
    {
        $warehouses = Warehouse::where('area_id', $id_area)->with('area')->get();

        //dd(datatables($materials)->toJson());
        return datatables($warehouses)->toJson();
    }
}
