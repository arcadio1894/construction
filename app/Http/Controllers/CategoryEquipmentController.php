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

    public function edit(CategoryEquipment $categoryEquipment)
    {
        //
    }

    public function update(Request $request, CategoryEquipment $categoryEquipment)
    {
        //
    }

    public function destroy(CategoryEquipment $categoryEquipment)
    {
        //
    }
}
