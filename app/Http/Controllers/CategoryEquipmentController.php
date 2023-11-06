<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'description' => 'required|string',
                'editImage' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $categoryEquipment = CategoryEquipment::find($id);

            if (!$categoryEquipment) {
                return response()->json(['message' => 'Categoría de equipo no encontrada'], 404);
            }

            $categoryEquipment->description = $request->input('description');
            //dd($request->file('editImage'));
            if (!$request->file('editImage')) {
                if ( $categoryEquipment->image == "no_image.png" )
                {
                    $categoryEquipment->image = "no_image.png";
                    $categoryEquipment->save();
                }

            } else {
                if ( $categoryEquipment->image  !="no_image.png" )
                {
                    $path = public_path().'/images/categoryEquipment/'.$categoryEquipment->image;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $path = public_path().'/images/categoryEquipment/';
                $image = $request->file('editImage');
                $filename = $categoryEquipment->id . '.JPG';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'JPG');
                $categoryEquipment->image = $filename;
                $categoryEquipment->save();
            }


            //$categoryEquipment->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Categoría de equipo actualizada con éxito.'], 200);
    }


    public function destroy($id) {
        try {

            $categoryEquipment = CategoryEquipment::find($id);

            if (!$categoryEquipment) {
                return response()->json(['message' => 'La categoría de equipo no se encontró'], 404);
            }


            $categoryEquipment->delete();


            return response()->json(['message' => 'Categoría de equipo eliminada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar la categoría de equipo'], 500);
        }
    }

    public function restore($id)
    {
        $categoryEquipment = CategoryEquipment::withTrashed()->find($id);

        if (!$categoryEquipment) {
            return response()->json(['message' => 'Categoría de equipo no encontrada'], 404);
        }

        $categoryEquipment->restore();

        return response()->json(['message' => 'Categoría de equipo restaurada con éxito'], 200);
    }

    public function eliminated()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('categoryEquipment.eliminated', compact('permissions'));
    }
    public function getDataCategoryEquipmentEliminated(Request $request, $pageNumber = 1){
        $perPage = 4;

        $nameCategoryEquipment = $request->get('name_category_equipment');

        $query = CategoryEquipment::onlyTrashed()->withTrashed()->orderBy('description', 'ASC');

        if ($nameCategoryEquipment) {
            $query->where('description', $nameCategoryEquipment);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $categoryEquipments = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get(); // Recupera los registros eliminados.

        $arrayCategoryEquipments = [];

        foreach ($categoryEquipments as $categoryEquipment) {
            array_push($arrayCategoryEquipments, [
                "id" => $categoryEquipment->id,
                "description" => $categoryEquipment->description,
                "image" => $categoryEquipment->image,
                "number" => $categoryEquipment->default_equipments->count(),
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords,
        ];

        return ['data' => $arrayCategoryEquipments, 'pagination' => $pagination];
    }
}
