<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteMaterialTypeRequest;
use App\Http\Requests\StoreMaterialTypeRequest;
use App\Http\Requests\UpdateMaterialTypeRequest;
use App\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialTypeController extends Controller
{
 
    public function index()
    {
        $materialtypes = MaterialType::all();
        //$permissions = Permission::all();

        return view('materialtype.index', compact('materialtypes'));
    }

    public function store(StoreMaterialTypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

                $materialType = MaterialType::create([
                    'name' => $request->get('name'),
                    'length' => $request->get('length'),
                    'width' => $request->get('width'),
                    'weight' => $request->get('weight'),
                    
                ]);

        DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Tipo de Material guardado con éxito.'], 200);
    }

    public function update(UpdateMaterialTypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $materialType = MaterialType::find($request->get('materialtype_id'));

            $materialType->name = $request->get('name');
            $materialType->length = $request->get('length');
            $materialType->width = $request->get('width');
            $materialType->weight = $request->get('weight');
            $materialType->save();

        DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tipo de material modificado con éxito.','url'=>route('materialtype.index')], 200);
    }

    public function destroy(DeleteMaterialTypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $materialtype = MaterialType::find($request->get('materialtype_id'));

            $materialtype->delete();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tipo de material eliminado con éxito.'], 200);
    }

    public function create()
    {
        return view('materialtype.create');
    }

    public function edit($id)
    {
        $materialtype = MaterialType::find($id);
        return view('materialtype.edit', compact('materialtype'));
    }

    public function getMaterialTypes()
    {
        $materialtypes = MaterialType::select('id', 'name', 'length', 'width', 'weight') -> get();
        return datatables($materialtypes)->toJson();
        //dd(datatables($customers)->toJson());
    }
    
}
