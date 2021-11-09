<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\DeleteSubcategoryRequest;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('subcategory.index', compact('subcategories', 'permissions'));
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            Subcategory::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'category_id' => $request->get('category_id'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Subcategoría guardada con éxito.'], 200);
    }

    public function update(UpdateSubcategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $subcategory = Subcategory::find($request->get('subcategory_id'));

            $subcategory->name = $request->get('name');
            $subcategory->description = $request->get('description');
            $subcategory->category_id = $request->get('category_id');
            $subcategory->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Subcategoría modificada con éxito.','url'=>route('subcategory.index')], 200);
    }

    public function destroy(DeleteSubcategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $subcategory = Subcategory::find($request->get('subcategory_id'));

            $subcategory->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Subcategoría eliminada con éxito.'], 200);
    }

    public function create()
    {
        $categories = Category::all();
        return view('subcategory.create', compact('categories'));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $subcategory = Subcategory::with('category')->find($id);
        return view('subcategory.edit', compact('categories', 'subcategory'));
    }


    public function getSubcategories()
    {
        $subcategories = Subcategory::with('category')->get();
        //dd($examplers);
        return datatables($subcategories)->toJson();
        //dd(datatables($customers)->toJson());
    }
}
