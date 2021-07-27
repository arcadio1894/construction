<?php

namespace App\Http\Controllers;

use App\Typescrap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class TypescrapController extends Controller
{
    public function index()
    {
        $typescraps = Typescrap::all();
        //$permissions = Permission::all();
        return view('typescrap.index', compact('typescraps'));
    }

    public function store(StoreTypeScrapRequest $request)
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


    public function getTypeScraps()
    {
        $typescraps = Typescrap::select('id', 'name', 'length', 'width') -> get();
        return datatables($typescraps)->toJson();
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
