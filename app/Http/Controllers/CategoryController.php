<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('category.index', compact('categories', 'permissions'));
    }


    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $category = Category::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
               

            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Categoría de material guardado con éxito.'], 200);
    }


    public function update(UpdateCategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $category = Category::find($request->get('category_id'));

            $category->name = $request->get('name');
            $category->description = $request->get('description');
            $category->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Categoría de material modificado con éxito.','url'=>route('category.index')], 200);
    }


    public function destroy(DeleteCategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $category = Category::find($request->get('category_id'));

            $category->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Categoría de material eliminado con éxito.'], 200);
    }


    public function create()
    {
        return view('category.create');
    }

    public function show(Category $category)
    {
        //
    }


    public function edit($id)
    {
        $category = Category::find($id);
        return view('category.edit', compact('category'));
    }   


    public function getCategories()
    {
        $categories = Category::select('id', 'name', 'description') -> get();
        return datatables($categories)->toJson();
        //dd(datatables($customers)->toJson());
    }

    public function getSubcategoryByCategory($id)
    {
        $subcategories = Subcategory::where('category_id', $id)->get();
        $array = [];
        foreach ( $subcategories as $subcategory )
        {
            array_push($array, ['id'=> $subcategory->id, 'subcategory' => $subcategory->name]);
        }

        //dd($array);
        return $array;
    }
}
