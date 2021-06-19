<?php

namespace App\Http\Controllers;

use App\Area;
use App\Container;
use App\Http\Requests\DeleteContainerRequest;
use App\Http\Requests\StoreContainerRequest;
use App\Http\Requests\UpdateContainerRequest;
use App\Level;
use App\Location;
use App\Shelf;
use App\Warehouse;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    public function index($nivel, $anaquel, $warehouse, $area)
    {
        $area = Area::find($area);
        $warehouse = Warehouse::find($warehouse);
        $shelf = Shelf::find($anaquel);
        $level = Level::find($nivel);
        //dd($area);
        return view('inventory.containers', compact('area', 'warehouse', 'shelf', 'level'));

    }

    public function create()
    {
        //
    }

    public function store(StoreContainerRequest $request)
    {
        $validated = $request->validated();

        $container = Container::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'level_id' => $request->get('level_id'),
        ]);

        return response()->json(['message' => 'Contenedor guardado con éxito.'], 200);

    }

    public function show(Container $container)
    {
        //
    }

    public function edit(Container $container)
    {
        //
    }

    public function update(UpdateContainerRequest $request)
    {
        $validated = $request->validated();

        $container = Container::find($request->get('container_id'));

        $container->name = $request->get('name');
        $container->comment = $request->get('comment');

        $container->save();

        return response()->json(['message' => 'Contenedor modificado con éxito.'], 200);

    }

    public function destroy(DeleteContainerRequest $request)
    {
        $validated = $request->validated();

        $container = Container::find($request->get('container_id'));

        $container->delete();

        return response()->json(['message' => 'Contenedor eliminado con éxito.'], 200);

    }

    public function getContainers( $id_level )
    {
        $containers = Container::where('level_id', $id_level)->get();

        //dd(datatables($materials)->toJson());
        return datatables($containers)->toJson();
    }
}
