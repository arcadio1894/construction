<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\DeleteMaterialRequest;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Material;
use App\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{

    public function index()
    {
        return view('material.index');
    }

    public function create()
    {
        $categories = Category::all();
        $materialTypes = MaterialType::all();
        return view('material.create', compact('categories', 'materialTypes'));
    }

    public function store(StoreMaterialRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $material = Material::create([
                'description' => $request->get('description'),
                'measure' => $request->get('measure'),
                'unit_measure' => $request->get('unit_measure'),
                'stock_max' => $request->get('stock_max'),
                'stock_min' => $request->get('stock_min'),
                'unit_price' => $request->get('unit_price'),
                'stock_current' => 0,
                'priority' => 'Aceptable',
                'material_type_id' => $request->get('material_type'),
                'category_id' => $request->get('category')
            ]);

            $length = 5;
            $string = $material->id;
            $code = 'P-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            //output: 0012345

            $material->code = $code;
            $material->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $material->image = 'no_image.png';
                $material->save();
            } else {
                $path = public_path().'/images/material/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $material->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $material->image = $filename;
                $material->save();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Material guardado con éxito.'], 200);

    }

    public function show(Material $material)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateMaterialRequest $request)
    {
        //
    }

    public function destroy(DeleteMaterialRequest $request)
    {
        //
    }

    public function getAllMaterials()
    {
        $materials = Material::with(['category', 'materialType'])->get();

        //dd(datatables($materials)->toJson());
        return datatables($materials)->toJson();
    }
}
