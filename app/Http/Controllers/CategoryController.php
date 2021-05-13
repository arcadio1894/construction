<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        //
    }


    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
           

        ]);
        return response()->json(['message' => 'Categoría de material guardado con éxito.'], 200);
    }


    public function update(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($request->get('category_id'));

        $category->name = $request->get('name');
        $category->description = $request->get('description');
       
        $customer->save();

        return response()->json(['message' => 'Categoría de material modificado con éxito.'], 200);
    }


    public function destroy(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($request->get('category_id'));

        $category->delete();

        return response()->json(['message' => 'Categoría de material eliminado con éxito.'], 200);
    }


    public function create()
    {
        //
    }
    public function show(Category $category)
    {
        //
    }


    public function edit(Category $category)
    {
        //
    }
}
