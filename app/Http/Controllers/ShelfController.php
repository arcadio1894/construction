<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Requests\DeleteShelfRequest;
use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Shelf;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
