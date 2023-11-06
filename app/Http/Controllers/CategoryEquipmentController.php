<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
use App\Http\Requests\StoreCategoryEquipmentsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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

    public function store(StoreCategoryEquipmentsRequest $request)
    {
        DB::beginTransaction();
        try {
            $categoryEquipment = CategoryEquipment::create([
                'description' => $request->get('description')
            ]);
            if (!$request->file('image')) {
                $categoryEquipment->image = 'no_image.png';
                $categoryEquipment->save();

            } else {
                $path = public_path().'/images/categoryEquipment/';
                $image = $request->file('image');
                $filename = $categoryEquipment->id . '.JPG';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'JPG');
                $categoryEquipment->image = $filename;
                $categoryEquipment->save();
            }
            DB::commit();
        }catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Categoría de equipo creada con éxito.'], 200);
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
