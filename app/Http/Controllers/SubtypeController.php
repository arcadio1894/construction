<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSubtypeRequest;
use App\Http\Requests\StoreSubtypeRequest;
use App\Http\Requests\UpdateSubtypeRequest;
use App\MaterialType;
use App\Subtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubtypeController extends Controller
{
    public function index()
    {
        $subtypes = Subtype::all();
        //$permissions = Permission::all();

        return view('subtype.index', compact('subtypes'));
    }

    public function store(StoreSubtypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $materialType = Subtype::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'material_type_id' => $request->get('material_type_id'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'SubTipo guardado con éxito.'], 200);
    }

    public function update(UpdateSubtypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $subType = Subtype::find($request->get('subtype_id'));

            $subType->name = $request->get('name');
            $subType->description = $request->get('description');
            $subType->material_type_id = $request->get('material_type_id');
            $subType->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sub Tipo modificado con éxito.','url'=>route('subtype.index')], 200);
    }

    public function destroy(DeleteSubtypeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $subtype = Subtype::find($request->get('subtype_id'));

            $subtype->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sub Tipo eliminado con éxito.'], 200);
    }

    public function create()
    {
        $materialTypes = MaterialType::all();
        return view('subtype.create', compact('materialTypes'));
    }

    public function edit($id)
    {
        $materialTypes = MaterialType::all();
        $subtype = Subtype::find($id);
        return view('subtype.edit', compact('subtype', 'materialTypes'));
    }

    public function getSubTypes()
    {
        $subtypes = Subtype::with('materialType')->get();
        return datatables($subtypes)->toJson();
        //dd(datatables($customers)->toJson());
    }

    public function getSubTypesByType($id)
    {
        $subtypes = Subtype::where('material_type_id', $id)->get();
        $array = [];
        foreach ( $subtypes as $subtype )
        {
            array_push($array, ['id'=> $subtype->id, 'subtype' => $subtype->name]);
        }

        //dd($array);
        return $array;
    }
}
