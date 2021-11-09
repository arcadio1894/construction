<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Requests\DeleteLevelRequest;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Level;
use App\Shelf;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function index($anaquel, $warehouse, $area)
    {
        $area = Area::find($area);
        $warehouse = Warehouse::find($warehouse);
        $shelf = Shelf::find($anaquel);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        //dd($area);
        return view('inventory.levels', compact('area', 'warehouse', 'shelf', 'permissions'));

    }

    public function create()
    {
        //
    }

    public function store(StoreLevelRequest $request)
    {
        $validated = $request->validated();

        $Level = Level::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'shelf_id' => $request->get('shelf_id'),
        ]);

        return response()->json(['message' => 'Nivel guardado con éxito.'], 200);

    }

    public function show(Level $level)
    {
        //
    }

    public function edit(Level $level)
    {
        //
    }

    public function update(UpdateLevelRequest $request)
    {
        $validated = $request->validated();

        $level = Level::find($request->get('level_id'));

        $level->name = $request->get('name');
        $level->comment = $request->get('comment');

        $level->save();

        return response()->json(['message' => 'Nivel modificado con éxito.'], 200);

    }

    public function destroy(DeleteLevelRequest $request)
    {
        $validated = $request->validated();

        $level = Level::find($request->get('level_id'));

        $level->delete();

        return response()->json(['message' => 'Nivel eliminado con éxito.'], 200);

    }

    public function getLevels( $id_shelf )
    {
        $levels = Level::where('shelf_id', $id_shelf)->get();

        //dd(datatables($materials)->toJson());
        return datatables($levels)->toJson();
    }
}
