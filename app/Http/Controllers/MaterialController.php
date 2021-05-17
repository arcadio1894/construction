<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index()
    {
        return view('material.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Material $material)
    {
        //
    }

    public function edit(Material $material)
    {
        //
    }

    public function update(Request $request, Material $material)
    {
        //
    }

    public function destroy(Material $material)
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
