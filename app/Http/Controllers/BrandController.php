<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Exampler;
use App\Http\Requests\DeleteBrandRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('brand.index', compact('brands', 'permissions'));
    }

    public function store(StoreBrandRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $brand = Brand::create([
                'name' => $request->get('name'),
                'comment' => $request->get('comment'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Marca de material guardado con éxito.'], 200);
    }

    public function update(UpdateBrandRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $brand = Brand::find($request->get('brand_id'));

            $brand->name = $request->get('name');
            $brand->comment = $request->get('comment');
            $brand->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Marca de material modificada con éxito.','url'=>route('brand.index')], 200);
    }

    public function destroy(DeleteBrandRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $brand = Brand::find($request->get('brand_id'));

            $brand->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Marca de material eliminada con éxito.'], 200);
    }

    public function create()
    {
        return view('brand.create');
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('brand.edit', compact('brand'));
    }


    public function getBrands()
    {
        $brands = Brand::select('id', 'name', 'comment') -> get();
        return datatables($brands)->toJson();
        //dd(datatables($customers)->toJson());
    }

    public function getJsonBrands($id)
    {
        $examplers = Exampler::where('brand_id', $id)->get();
        $array = [];
        foreach ( $examplers as $exampler )
        {
            array_push($array, ['id'=> $exampler->id, 'exampler' => $exampler->name]);
        }

        //dd($array);
        return $array;
    }
}
