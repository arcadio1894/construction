<?php

namespace App\Http\Controllers;

use App\AreaWorker;
use App\Http\Requests\DeleteAreaWorkerRequest;
use App\Http\Requests\StoreAreaWorkerRequest;
use App\Http\Requests\UpdateAreaWorkerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaWorkerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('areaWorker.index', compact('permissions'));
    }

    public function create()
    {
        return view('areaWorker.create');
    }

    public function store(StoreAreaWorkerRequest $request)
    {
        $validated = $request->validated();

        $area = AreaWorker::create([
            'name' => $request->get('name'),
        ]);

        return response()->json(['message' => 'Área guardada con éxito.'], 200);

    }

    public function edit($id)
    {
        $areaWorker = AreaWorker::find($id);
        return view('areaWorker.edit', compact('areaWorker'));
    }

    public function update(UpdateAreaWorkerRequest $request)
    {
        $validated = $request->validated();

        $area = AreaWorker::find($request->get('areaWorker_id'));

        $area->name = $request->get('name');

        $area->save();

        return response()->json(['message' => 'Área modificada con éxito.'], 200);

    }

    public function destroy(DeleteAreaWorkerRequest $request)
    {
        $validated = $request->validated();

        $area = AreaWorker::find($request->get('areaWorker_id'));

        $area->delete();

        return response()->json(['message' => 'Área eliminada con éxito.'], 200);

    }

    public function getAreas()
    {
        $areas = AreaWorker::select('id', 'name')->get();
        return datatables($areas)->toJson();
    }
}
