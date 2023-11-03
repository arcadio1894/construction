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

    public function getDataCategoryEquipment(Request $request, $pageNumber = 1){
        $perPage = 4;

        $nameCategoryEquipment = $request->get('name_category_equipment');

        // Aplicar filtros si se proporcionan
        if ($nameCategoryEquipment) {
            $query = CategoryEquipment::where('description', $nameCategoryEquipment)
                ->orderBy('description', 'ASC')
                ->get();
        } else {
            $query = CategoryEquipment::orderBy('description', 'ASC')
                ->get();
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $categoryEquipments = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arrayCategoryEquipments = [];

        foreach ( $categoryEquipments as $categoryEquipment )
        {
            array_push($arrayCategoryEquipments, [
                "id" => $categoryEquipment->id,
                "description" => $categoryEquipment->description,
                "image" => $categoryEquipment->image,
                "number" => $categoryEquipment->default_equipments->count()
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayCategoryEquipments, 'pagination' => $pagination];
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
