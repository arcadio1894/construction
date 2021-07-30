<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteQualityRequest;
use App\Http\Requests\StoreQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use App\Quality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QualityController extends Controller
{
    public function index()
    {
        $qualities = Quality::all();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('quality.index', compact('qualities', 'permissions'));
    }

    public function store(StoreQualityRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $quality = Quality::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Calidad de material guardado con éxito.'], 200);
    }

    public function update(UpdateQualityRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $quality = Quality::find($request->get('quality_id'));

            $quality->name = $request->get('name');
            $quality->description = $request->get('description');
            $quality->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Calidad de material modificada con éxito.','url'=>route('quality.index')], 200);
    }

    public function destroy(DeleteQualityRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $quality = Quality::find($request->get('quality_id'));

            $quality->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Calidad de material eliminada con éxito.'], 200);
    }

    public function create()
    {
        return view('quality.create');
    }

    public function edit($id)
    {
        $quality = Quality::find($id);
        return view('quality.edit', compact('quality'));
    }


    public function getQualities()
    {
        $qualities = Quality::select('id', 'name', 'description') -> get();
        return datatables($qualities)->toJson();
        //dd(datatables($customers)->toJson());
    }

}
