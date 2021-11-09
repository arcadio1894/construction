<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Requests\DeleteAreaRequest;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('inventory.areas', compact('permissions'));
    }

    public function create()
    {
        //
    }

    public function store(StoreAreaRequest $request)
    {
        $validated = $request->validated();

        $area = Area::create([
            'name' => $request->get('name'),
            'comment' => $request->get('comment'),
        ]);

        return response()->json(['message' => 'Área guardada con éxito.'], 200);

    }

    public function show(Area $area)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateAreaRequest $request)
    {
        $validated = $request->validated();

        $area = Area::find($request->get('area_id'));

        $area->name = $request->get('name');
        $area->comment = $request->get('comment');

        $area->save();

        return response()->json(['message' => 'Área modificada con éxito.'], 200);

    }

    public function destroy(DeleteAreaRequest $request)
    {
        $validated = $request->validated();

        $area = Area::find($request->get('area_id'));

        $area->delete();

        return response()->json(['message' => 'Área eliminada con éxito.'], 200);

    }

    public function getAreas()
    {
        $areas = Area::select('id', 'name', 'comment')->get();
        return datatables($areas)->toJson();
    }
}
