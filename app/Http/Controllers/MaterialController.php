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

            $user = Material::create([
                'description',
                'measure',
                'unit_measure',
                'stock_max',
                'stock_min',
                'stock_current',
                'priority',
                'unit_price',
                'image',
                'material_type_id',
                'category_id'
            ]);

            // Sincronizar con roles
            $roles = $request->get('roles');
            //var_dump($roles);
            $user->syncRoles($roles);

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $user->image = 'no_image.png';
                $user->save();
            } else {
                $path = public_path().'/images/users/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $user->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $user->image = $filename;
                $user->save();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Usuario guardado con éxito.'], 200);

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
