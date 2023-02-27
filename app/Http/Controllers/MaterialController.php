<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\CategoryInvoice;
use App\Exampler;
use App\Http\Requests\DeleteMaterialRequest;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Material;
use App\MaterialType;
use App\Quality;
use App\Specification;
use App\Item;
use APP\DetailEntry;
use App\Subcategory;
use App\Subtype;
use App\Typescrap;
use App\UnitMeasure;
use App\Warrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('material.index', compact('permissions'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $warrants = Warrant::all();
        $qualities = Quality::all();
        $typescraps = Typescrap::all();
        $unitMeasures = UnitMeasure::all();
        return view('material.create', compact('categories', 'warrants', 'brands', 'qualities', 'typescraps', 'unitMeasures'));
    }

    public function store(StoreMaterialRequest $request)
    {
        //dd($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $material = Material::create([
                'description' => $request->get('description'),
                'measure' => $request->get('measure'),
                'unit_measure_id' => $request->get('unit_measure'),
                'stock_max' => $request->get('stock_max'),
                'stock_min' => $request->get('stock_min'),
                'unit_price' => $request->get('unit_price'),
                'stock_current' => 0,
                'priority' => 'Aceptable',
                'category_id' => $request->get('category'),
                'subcategory_id' => $request->get('subcategory'),
                'material_type_id' => $request->get('type'),
                'subtype_id' => $request->get('subtype'),
                'brand_id' => $request->get('brand'),
                'exampler_id' => $request->get('exampler'),
                'warrant_id' => $request->get('warrant'),
                'quality_id' => $request->get('quality'),
                'typescrap_id' => $request->get('typescrap'),
                'enable_status' => true
            ]);

            $length = 5;
            $string = $material->id;
            $code = 'P-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            //output: 0012345

            $material->code = $code;
            $material->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $material->image = 'no_image.png';
                $material->save();
            } else {
                $path = public_path().'/images/material/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $material->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $material->image = $filename;
                $material->save();
            }

            // TODO: Insertamos las especificaciones

            $specifications = $request->get('specifications');
            $contents = $request->get('contents');
            if ( $request->has('specifications') )
            {
                for ( $i=0; $i< sizeof($specifications); $i++ )
                {
                    Specification::create([
                        'name' => $specifications[$i],
                        'content' => $contents[$i],
                        'material_id' => $material->id
                    ]);
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Material guardado con éxito.'], 200);

    }

    public function show(Material $material)
    {
        //
    }

    public function edit($id)
    {
        $specifications = Specification::where('material_id', $id)->get();
        $brands = Brand::all();
        $categories = Category::all();
        $materialTypes = MaterialType::all();
        $material = Material::with(['category', 'materialType', ])->find($id);
        $warrants = Warrant::all();
        $qualities = Quality::all();
        $typescraps = Typescrap::all();
        $unitMeasures = UnitMeasure::all();
        return view('material.edit', compact('unitMeasures','typescraps','qualities','warrants','specifications', 'brands', 'categories', 'materialTypes', 'material'));

    }

    public function update(UpdateMaterialRequest $request)
    {
        //dd($request->get('typescrap'));
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $material = Material::find($request->get('material_id'));

            $material->description = $request->get('description');
            $material->measure = $request->get('measure');
            $material->unit_measure_id = $request->get('unit_measure');
            $material->stock_max = $request->get('stock_max');
            $material->stock_min = $request->get('stock_min');
            $material->unit_price = $request->get('unit_price');
            $material->stock_current = $request->get('stock_current');
            $material->priority = $request->get('priority');
            $material->category_id = $request->get('category');
            $material->subcategory_id = $request->get('subcategory');
            $material->material_type_id = $request->get('type');
            $material->subtype_id = $request->get('subtype');
            $material->brand_id = $request->get('brand');
            $material->exampler_id = $request->get('exampler');
            $material->warrant_id = $request->get('warrant');
            $material->quality_id = $request->get('quality');
            $material->typescrap_id = $request->get('typescrap');
            $material->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                if ($material->image == 'no_image.png' || $material->image == null) {
                    $material->image = 'no_image.png';
                    $material->save();
                }
            } else {
                $path = public_path().'/images/material/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $material->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $material->image = $filename;
                $material->save();
            }

            // TODO: Insertamos las especificaciones
            $specifications = $request->get('specifications');
            $contents = $request->get('contents');
            if ( $request->has('specifications') )
            {
                Specification::where('material_id', $material->id)->delete();

                for ( $i=0; $i< sizeof($specifications); $i++ )
                {
                    Specification::create([
                        'name' => $specifications[$i],
                        'content' => $contents[$i],
                        'material_id' => $material->id
                    ]);
                }
            } else {
                Specification::where('material_id', $material->id)->delete();
            }

            if ($material->wasChanged('typescrap_id') )
            {
                if ( $request->get('typescrap') != null )
                {
                    $typeScrap = Typescrap::find($request->get('typescrap'));
                    $items = Item::where('material_id', $material->id)
                        ->whereIn('state_item', ['entered', 'exited'])
                        ->get();
                    foreach ( $items as $item )
                    {
                        $item->length = (float)$typeScrap->length;
                        $item->width = (float)$typeScrap->width;
                        $item->typescrap_id = $typeScrap->id;
                        $item->save();
                    }
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function destroy(DeleteMaterialRequest $request)
    {
        $validated = $request->validated();

        $material = Material::find($request->get('material_id'));
        Specification::where('material_id', $request->get('material_id'))->delete();

        $material->delete();

        return response()->json(['message' => 'Material eliminado con éxito.'], 200);

    }

    public function getAllMaterials()
    {
        $materials = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 1)
            ->where('category_id', '<>', 8)
            ->get();
            //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();


        //dd($materials);
        //dd(datatables($materials)->toJson());
        return datatables($materials)->toJson();
    }

    public function indexMaterialsActivos()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('material.indexActivosFijos', compact('permissions'));
    }

    public function getAllMaterialsActivosFijos()
    {
        $materials = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 1)
            ->where('category_id', '=', 8)
            ->get();
        //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();


        //dd($materials);
        //dd(datatables($materials)->toJson());
        return datatables($materials)->toJson();
    }

    public function getAllMaterialsSinOp()
    {
        $begin = microtime(true);
        $materials = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 1)
            ->get();
        //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();

        $end = microtime(true) - $begin;

        dump($end. ' segundos');
        dd();
        //dd(datatables($materials)->toJson());
        //return datatables($materials)->toJson();
    }

    public function getAllMaterialsOp()
    {
        $begin = microtime(true);
        $materials = Material::where('enable_status', 1)
            ->get();

        $array = [];

        foreach ($materials as $material) {
            $unit = UnitMeasure::find($material->unit_measure_id);
            $category = Category::find($material->category_id);
            $subcategory = Subcategory::find($material->subcategory_id);
            $material_type = MaterialType::find($material->material_type_id);
            $sub_type = Subtype::find($material->sub_type_id);
            $warrant = Warrant::find($material->warrant_id);
            $quality = Quality::find($material->quality_id);
            $brand = Brand::find($material->brand_id);
            $exampler = Exampler::find($material->exampler_id);
            $type_scrap = Typescrap::find($material->type_scrap_id);
            array_push($array, [
                'id'=> $material->id,
                'description' => $material->full_description,
                'measure' => $material->measure,
                'unit_measure' => ($material->unit_measure_id == null) ? '': $unit->name,
                'stock_max' => $material->stock_max,
                'stock_min'=>$material->stock_min,
                'stock_current'=>$material->stock_current,
                'unit_price'=>$material->unit_price,
                'image'=>$material->image,
                'category' => ($material->category_id == null) ? '': $category->name,
                'subcategory' => ($material->subcategory_id == null) ? '': $subcategory->name,
                'material_type' => ($material->material_type_id == null) ? '': $material_type->name,
                'sub_type' => ($material->sub_type_id == null) ? '': $sub_type->name,
                'warrant' => ($material->warrant_id == null) ? '': $warrant->name,
                'quality' => ($material->quality_id == null) ? '': $quality->name,
                'brand' => ($material->brand_id == null) ? '': $brand->name,
                'exampler' => ($material->exampler_id == null) ? '': $exampler->name,
                'type_scrap' => ($material->type_scrap_id == null) ? '': $type_scrap->name,
            ]);

        }
        //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();

        $end = microtime(true) - $begin;

        dump($end. ' segundos');
        dd();
        //dd(datatables($materials)->toJson());
        //return datatables($materials)->toJson();
    }

    public function getJsonMaterialsTransfer()
    {
        $materials = Material::where('enable_status', 1)->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'code' => $material->code, ]);
        }

        //dd($materials);
        return $array;
    }

    public function getJsonMaterials()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('enable_status', 1)->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'unit' => $material->unitMeasure->name, 'code' => $material->code, 'price'=>$material->unit_price, 'typescrap'=>$material->typescrap_id, 'full_typescrap'=>$material->typeScrap, 'stock_current'=>$material->stock_current]);
        }

        //dd($materials);
        return $array;
    }

    public function getJsonMaterialsQuote()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->whereNotIn('category_id', [2])
            ->where('category_id', '<>', 8)
            ->where('enable_status', 1)->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'unit' => $material->unitMeasure->name, 'code' => $material->code]);
        }

        //dd($materials);
        return $array;
    }

    public function getJsonMaterialsScrap()
    {
        $materials = Material::with('subcategory', 'materialType', 'subtype', 'warrant', 'quality')
            ->whereNotNull('typescrap_id')
            ->where('enable_status', 1)
            ->get();
        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'code' => $material->code , 'unit' => $material->unitMeasure->name, 'typescrap'=>$material->typescrap_id]);
        }

        //dd($materials);
        return $array;
    }

    public function getItems($id)
    {
        /*
        $items = Item::where('material_id', $id)->get();
        $brands = Brand::all();
        $categories = Category::all();
        $materialTypes = MaterialType::all();
        $material = Material::with(['category', 'materialType'])->find($id);
        return view('material.edit', compact('items', 'brands', 'categories', 'materialTypes', 'material'));
        */

        $material = Material::find($id);
        //$items = Item::where('material_id', $id)->get();
        //return view('material.items', compact('items', 'material'));
        return view('material.items', compact('material'));

    }

    public function getItemsMaterial($id)
    {

        $items = Item::where('material_id', $id)
            ->whereIn('state_item', ['entered', 'scraped'])
            ->with(['location' => function ($query) {
                $query->with(['area', 'warehouse', 'shelf', 'level', 'container', 'position']);
            }])
            ->with('material')
            ->with('typescrap')
            ->with('DetailEntry')->get();

        //dd(datatables($items)->toJson());
        return datatables($items)->toJson();

    }

    public function disableMaterial(Request $request)
    {

        DB::beginTransaction();
        try {
            $material = Material::find($request->get('material_id'));
            $material->enable_status = 0;
            $material->save();
            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Material inhabilitado con éxito.'], 200);

    }

    public function enableMaterial(Request $request)
    {
        DB::beginTransaction();
        try {
            $material = Material::find($request->get('material_id'));
            $material->enable_status = 1;
            $material->save();
            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Material habilitado con éxito.'], 200);

    }

    public function getAllMaterialsDisable()
    {
        $materials = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 0)
            ->where('category_id', '<>', 8)
            ->get();
        //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();


        //dd($materials);
        //dd(datatables($materials)->toJson());
        return datatables($materials)->toJson();
    }

    public function indexEnable()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('material.enable', compact('permissions'));
    }
}
