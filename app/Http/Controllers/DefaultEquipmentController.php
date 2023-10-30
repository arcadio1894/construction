<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
use App\DefaultEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultEquipmentController extends Controller
{
    public function index($category_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $category = CategoryEquipment::find($category_id);
        return view('defaultEquipment.index', compact('permissions', 'category'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function edit(DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function update(Request $request, DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function destroy(DefaultEquipment $defaultEquipment)
    {
        //
    }
}
