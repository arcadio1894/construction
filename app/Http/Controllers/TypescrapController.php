<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTypeScrapRequest;
use App\Http\Requests\StoreTypeScrapRequest;
use App\Http\Requests\UpdateTypeScrapRequest;
use App\Typescrap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class TypescrapController extends Controller
{
    public function index()
    {
        $typescraps = Typescrap::all();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('typescrap.index', compact('typescraps', 'permissions'));
    }

    public function store(StoreTypeScrapRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $typeScrap = Typescrap::create([
                'name' => $request->get('name'),
                'width' => $request->get('width'),
                'length' => $request->get('length'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Tipo de retacería guardado con éxito.'], 200);
    }

    public function update(UpdateTypeScrapRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $typeScrap = TypeScrap::find($request->get('typeScrap_id'));

            $typeScrap->name = $request->get('name');
            $typeScrap->width = $request->get('width');
            $typeScrap->length = $request->get('length');
            $typeScrap->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tipo de retacería modificada con éxito.','url'=>route('typescrap.index')], 200);
    }

    public function destroy(DeleteTypeScrapRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $typeScrap = TypeScrap::find($request->get('typeScrap_id'));

            $typeScrap->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tipo de retacería eliminada con éxito.'], 200);
    }

    public function create()
    {
        return view('typescrap.create');
    }

    public function edit($id)
    {
        $typeScrap = TypeScrap::find($id);
        return view('typescrap.edit', compact('typeScrap'));
    }


    public function getTypeScraps()
    {
        $typescraps = Typescrap::select('id', 'name', 'length', 'width') -> get();
        return datatables($typescraps)->toJson();
        //dd(datatables($customers)->toJson());
    }

}
