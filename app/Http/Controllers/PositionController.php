<?php

namespace App\Http\Controllers;

use App\Area;
use App\Container;
use App\Http\Requests\DeletePositionRequest;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Level;
use App\Location;
use App\Position;
use App\Shelf;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index($container, $nivel, $anaquel, $warehouse, $area)
    {
        $area = Area::find($area);
        $warehouse = Warehouse::find($warehouse);
        $shelf = Shelf::find($anaquel);
        $level = Level::find($nivel);
        $container = Level::find($container);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        //dd($area);
        return view('inventory.positions', compact('area', 'warehouse', 'shelf', 'level', 'container', 'permissions'));

    }

    public function create()
    {
        //
    }

    public function store(StorePositionRequest $request)
    {
        $validated = $request->validated();

        $position = Position::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'container_id' => $request->get('container_id'),
        ]);

        // Crear la ubicacion
        $container = Container::find($position->container->id);
        $level = Level::find($container->level->id);
        $shelf = Shelf::find($level->shelf->id);
        $warehouse = Warehouse::find($shelf->warehouse->id);
        $area = Area::find($warehouse->area->id);

        $location = Location::create([
            'area_id' => $area->id,
            'warehouse_id' => $warehouse->id,
            'shelf_id' => $shelf->id,
            'level_id' => $level->id,
            'container_id' => $container->id,
            'position_id' => $position->id,
            'description' => 'AR-'.$area->name.'|ALM-'.$warehouse->name.'|ANA-'.$shelf->name.'|NIV-'.$level->name.'|CONT-'.$container->name
        ]);

        return response()->json(['message' => 'Posición guardado con éxito.'], 200);

    }

    public function show(Position $position)
    {
        //
    }

    public function edit(Position $position)
    {
        //
    }

    public function update(UpdatePositionRequest $request)
    {
        $validated = $request->validated();

        $position = Position::find($request->get('position_id'));

        $position->name = $request->get('name');
        $position->comment = $request->get('comment');

        $position->save();

        return response()->json(['message' => 'Posición modificado con éxito.'], 200);

    }

    public function destroy(DeletePositionRequest $request)
    {
        $validated = $request->validated();

        $position = Position::find($request->get('position_id'));
        //dd($position);

        // Eliminar la ubicacion
        $container = Container::find($position->container->id);
        $level = Level::find($container->level->id);
        $shelf = Shelf::find($level->shelf->id);
        $warehouse = Warehouse::find($shelf->warehouse->id);
        $area = Area::find($warehouse->area->id);

        $location = Location::where('area_id', $area->id)
            ->where('warehouse_id', $warehouse->id)
            ->where('shelf_id', $shelf->id)
            ->where('level_id', $level->id)
            ->where('container_id', $container->id)
            ->where('position_id', $position->id)->first();

        //dd($location);

        $location->delete();

        $position->delete();

        return response()->json(['message' => 'Posición eliminada con éxito.'], 200);

    }

    public function getPositions( $id_container )
    {
        $positions = Position::where('container_id', $id_container)->get();

        //dd(datatables($materials)->toJson());
        return datatables($positions)->toJson();
    }
}
