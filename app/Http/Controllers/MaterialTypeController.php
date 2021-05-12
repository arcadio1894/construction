<?php

namespace App\Http\Controllers;

use App\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
 
    public function index()
    {
        //
    }

    public function store(StoreMaterialTypeRequest $request)
    {
        $validated = $request->validated();

        $materialType = MaterialType::create([
            'name' => $request->get('name'),
            'length' => $request->get('length'),
            'width' => $request->get('width'),
            'weight' => $request->get('weight'),
            
        ]);
        return response()->json(['message' => 'Tipo de Material guardado con éxito.'], 200);
    }

    public function update(UpdateMaterialTypeRequest $request)
    {
        $validated = $request->validated();

        $materialType = MaterialType::find($request->get('materialType_id'));

        $materialType->name = $request->get('name');
        $materialType->length = $request->get('length');
        $materialType->width = $request->get('width');
        $materialType->weight = $request->get('weight');
        
        $materialType->save();

        return response()->json(['message' => 'Tipo de material modificado con éxito.'], 200);
    }

    public function destroy(DeleteMaterialTypeReqiest $request)
    {
        $validated = $request->validated();

        $materialType = MaterialType::find($request->get('materialType_id'));

        $materialType->delete();

        return response()->json(['message' => 'Tipo de material eliminado con éxito.'], 200);
    }
    public function create()
    {
        //
    }
    public function edit(MaterialType $materialType)
    {
        //
    }
    
}
