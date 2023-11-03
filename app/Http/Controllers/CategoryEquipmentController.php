<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryEquipmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('categoryEquipment.index', compact('permissions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(CategoryEquipment $categoryEquipment)
    {
        //
    }

    public function edit()
    {

        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        return view('categoryEquipment.edit', compact('permissions'));
    }

    public function update(Request $request, CategoryEquipment $categoryEquipment)
    {
        //
    }

    public function destroy($id)
    {

        $categoryEquipment = CategoryEquipment::find($id);

        if (!$categoryEquipment) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $categoryEquipment->delete();

        return response()->json(['message' => 'Categoría eliminada con éxito'], 200);
    }
    public function restore($id)
    {

        $categoryEquipment = CategoryEquipment::withTrashed()->find($id);

        if (!$categoryEquipment) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $categoryEquipment->restore();

        return response()->json(['message' => 'Registro restaurado con éxito'], 200);

    }
    public function eliminated()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $trashedCategoryEquipments = CategoryEquipment::onlyTrashed()->get();

        return view('categoryEquipment.eliminated', compact('trashedCategoryEquipments','permissions'));
    }
}
