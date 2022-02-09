<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
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
                'typescrap_id' => $request->get('typescrap')

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
            //->where('description', 'not like', '%EDESCE%')
            ->get();
            //->get(['id', 'code', 'measure', 'stock_max', 'stock_min', 'stock_current', 'priority', 'unit_price', 'image', 'description'])->toArray();


        //dd($materials);
        //dd(datatables($materials)->toJson());
        return datatables($materials)->toJson();
    }

    public function getJsonMaterials()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'unit' => $material->unitMeasure->name, 'code' => $material->code, 'price'=>$material->unit_price]);
        }

        //dd($materials);
        return $array;
    }

    public function getJsonMaterialsQuote()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->whereNotIn('category_id', [2])->get();

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
}
