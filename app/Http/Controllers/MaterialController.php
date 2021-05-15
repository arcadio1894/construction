<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index()
    {
        //
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
        $roles = Material::select('id', 'name', 'description')->get();
        return datatables($roles)->toJson();
    }
}
