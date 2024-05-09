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
use Carbon\Carbon;
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

    public function listarActivosFijos()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('material.listarActivosIndex', compact('permissions'));
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
        $mat = null;
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
            $mat = $material;
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $mat->full_name = $material->full_description;
        $mat->save();

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

            if ($material->wasChanged('unit_price') )
            {
                $material->date_update_price = Carbon::now("America/Lima");
                $material->state_update_price = 1;
                $material->save();
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $material = Material::find($request->get('material_id'));
        $material->full_name = $material->full_description;
        $material->save();
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

    public function getDataMaterials(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $description = $request->input('description');
        $code = $request->input('code');
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');
        $material_type = $request->input('material_type');
        $sub_type = $request->input('sub_type');
        $cedula = $request->input('cedula');
        $calidad = $request->input('calidad');
        $marca = $request->input('marca');
        $retaceria = $request->input('retaceria');
        $rotation = $request->input('rotation');

        $query = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 1)
            ->where('category_id', '<>', 8)
            ->orderBy('rotation', "desc")
            ->orderBy('id');

        // Aplicar filtros si se proporcionan
        if ($description != "") {
            // Convertir la cadena de búsqueda en un array de palabras clave
            $keywords = explode(' ', $description);

            // Construir la consulta para buscar todas las palabras clave en el campo full_name
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('full_name', 'LIKE', '%' . $keyword . '%');
                }
            });

            // Asegurarse de que todas las palabras clave estén presentes en la descripción
            foreach ($keywords as $keyword) {
                $query->where('full_name', 'LIKE', '%' . $keyword . '%');
            }
        }

        if ($code != "") {
            $query->where('code', 'LIKE', '%'.$code.'%');
        }

        if ($category != "") {
            $query->where('category_id', $category);
        }

        if ($subcategory != "") {
            $query->where('subcategory_id', $subcategory);
        }

        if ($material_type != "") {
            $query->where('material_type_id', $material_type);
        }

        if ($sub_type != "") {
            $query->where('subtype_id', $sub_type);
        }

        if ($cedula != "") {
            $query->where('warrant_id', $cedula);
        }

        if ($calidad != "") {
            $query->where('quality_id', $calidad);
        }

        if ($marca != "") {
            $query->where('brand_id', $marca);
        }

        if ($retaceria != "") {
            $query->where('typescrap_id', $retaceria);
        }

        if ( $rotation != "" ) {
            $query->where('rotation', $rotation);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $materials = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $materials as $material )
        {
            $priority = '';
            if ( $material->stock_current > $material->stock_max ){
                $priority = 'Completo';
            } else if ( $material->stock_current == $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current > $material->stock_min && $material->stock_current < $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current == $material->stock_min ){
                $priority = 'Por agotarse';
            } else if ( $material->stock_current < $material->stock_min || $material->stock_current == 0 ){
                $priority = 'Agotado';
            }

            $rotacion = "";
            if ( $material->rotation == "a" )
            {
                $rotacion = '<span class="badge bg-success text-md">ALTA</span>';
            } elseif ( $material->rotation == "m" ) {
                $rotacion = '<span class="badge bg-warning text-md">MEDIA</span>';
            } else {
                $rotacion = '<span class="badge bg-danger text-md">BAJA</span>';
            }

            array_push($array, [
                "id" => $material->id,
                "codigo" => $material->code,
                "descripcion" => $material->full_name,
                "medida" => $material->measure,
                "unidad_medida" => ($material->unitMeasure == null) ? '':$material->unitMeasure->name,
                "stock_max" => $material->stock_max,
                "stock_min" => $material->stock_min,
                "stock_actual" => $material->stock_current,
                "prioridad" => $priority,
                "precio_unitario" => $material->unit_price,
                "categoria" => ($material->category == null) ? '': $material->category->name,
                "sub_categoria" => ($material->subcategory == null) ? '': $material->subcategory->name,
                "tipo" => ($material->materialType == null) ? '': $material->materialType->name,
                "sub_tipo" => ($material->subType == null) ? '': $material->subType->name,
                "cedula" => ($material->warrant == null) ? '':$material->warrant->name,
                "calidad" => ($material->quality == null) ? '': $material->quality->name,
                "marca" => ($material->brand == null) ? '': $material->brand->name,
                "modelo" => ($material->exampler == null) ? '': $material->exampler->name,
                "retaceria" => ($material->typeScrap == null) ? '':$material->typeScrap->name,
                "image" => ($material->image == null || $material->image == "" ) ? 'no_image.png':$material->image,
                "rotation" => $rotacion,
                "update_price" => $material->state_update_price
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

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function indexV2()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $arrayCategories = Category::where('id', '<>', 8)->select('id', 'name')->get()->toArray();

        $arrayCedulas = Warrant::select('id', 'name')->get()->toArray();

        $arrayCalidades = Quality::select('id', 'name')->get()->toArray();

        $arrayMarcas = Brand::select('id', 'name')->get()->toArray();

        $arrayRetacerias = Typescrap::select('id', 'name')->get()->toArray();

        $arrayRotations = [
            ["value" => "a", "display" => "ALTA"],
            ["value" => "m", "display" => "MEDIA"],
            ["value" => "b", "display" => "BAJA"]
        ];

        return view('material.indexv2', compact( 'permissions', 'arrayCategories', 'arrayCedulas', 'arrayCalidades', 'arrayMarcas', 'arrayRetacerias', 'arrayRotations'));

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

        $items = Item::with('material')->where('type', true)->get();
        $items_quantity = [];
        $materials_quantity = [];
        foreach ( $items as $item )
        {
            if ( $item->material->category_id != 8 )
            {
                array_push($items_quantity, array('material_id'=>$item->material_id,'quantity'=> (float)$item->percentage));
            }
        }

        $new_arr = array();
        foreach($items_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials_quantity = array_values($new_arr);

        $arrayMaterials = [];

        foreach($materials_quantity as $mat) {
            $material = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
                ->where('enable_status', 1)
                ->find($mat['material_id']);
            array_push($arrayMaterials, [
                'id'=> $material->id,
                'description' => $material->full_description,
                'code' => $material->code,
                'priority' => $material->priority,
                'measure' => $material->measure,
                'unit_measure' => ($material->unit_measure_id == null) ? '': $material->unitMeasure->name,
                'stock_max' => $material->stock_max,
                'stock_min'=>$material->stock_min,
                'quantity_items'=>$material->quantity_items,
                'stock_current'=>$mat['quantity'],
                'unit_price'=>$material->unit_price,
                'image'=>$material->image,
                'category' => ($material->category_id == null) ? '': $material->category->name,
                'subcategory' => ($material->subcategory_id == null) ? '': $material->subcategory->name,
                'material_type' => ($material->material_type_id == null) ? '': $material->materialType->name,
                'sub_type' => ($material->sub_type_id == null) ? '': $material->sub_type->name,
                'warrant' => ($material->warrant_id == null) ? '': $material->warrant->name,
                'quality' => ($material->quality_id == null) ? '': $material->quality->name,
                'brand' => ($material->brand_id == null) ? '': $material->brand->name,
                'exampler' => ($material->exampler_id == null) ? '': $material->exampler->name,
                'type_scrap' => ($material->type_scrap_id == null) ? '': $material->typeScrap->name,
            ]);
        }

        foreach($materials as $material) {
            array_push($arrayMaterials, [
                'id'=> $material->id,
                'description' => $material->full_description,
                'code' => $material->code,
                'priority' => $material->priority,
                'measure' => $material->measure,
                'unit_measure' => ($material->unit_measure_id == null) ? '': $material->unitMeasure->name,
                'stock_max' => $material->stock_max,
                'stock_min'=>$material->stock_min,
                'quantity_items'=>$material->quantity_items,
                'stock_current'=>$material->quantity_items,
                'unit_price'=>$material->unit_price,
                'image'=>$material->image,
                'category' => ($material->category_id == null) ? '': $material->category->name,
                'subcategory' => ($material->subcategory_id == null) ? '': $material->subcategory->name,
                'material_type' => ($material->material_type_id == null) ? '': $material->materialType->name,
                'sub_type' => ($material->sub_type_id == null) ? '': $material->sub_type->name,
                'warrant' => ($material->warrant_id == null) ? '': $material->warrant->name,
                'quality' => ($material->quality_id == null) ? '': $material->quality->name,
                'brand' => ($material->brand_id == null) ? '': $material->brand->name,
                'exampler' => ($material->exampler_id == null) ? '': $material->exampler->name,
                'type_scrap' => ($material->type_scrap_id == null) ? '': $material->typeScrap->name,
            ]);
        }

        //dd($arrayMaterials);
        //dd(datatables($materials)->toJson());
        return datatables($arrayMaterials)->toJson();
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
        $materials = Material::with('category:id,name', 'materialType:id,name','unitMeasure:id,name','subcategory:id,name','subType:id,name','exampler:id,name','brand:id,name','warrant:id,name','quality:id,name','typeScrap:id,name')
            ->where('enable_status', 1)
            ->get();

        $array = [];

        foreach ($materials as $material) {
            array_push($array, [
                'id'=> $material->id,
                'description' => $material->full_description,
                'code' => $material->code,
                'priority' => $material->priority,
                'measure' => $material->measure,
                'unit_measure' => ($material->unit_measure_id == null) ? '': $material->unitMeasure->name,
                'stock_max' => $material->stock_max,
                'stock_min'=>$material->stock_min,
                'stock_current'=>$material->stock_current,
                'unit_price'=>$material->unit_price,
                'image'=>$material->image,
                'category' => ($material->category_id == null) ? '': $material->category->name,
                'subcategory' => ($material->subcategory_id == null) ? '': $material->subcategory->name,
                'material_type' => ($material->material_type_id == null) ? '': $material->materialType->name,
                'sub_type' => ($material->sub_type_id == null) ? '': $material->sub_type->name,
                'warrant' => ($material->warrant_id == null) ? '': $material->warrant->name,
                'quality' => ($material->quality_id == null) ? '': $material->quality->name,
                'brand' => ($material->brand_id == null) ? '': $material->brand->name,
                'exampler' => ($material->exampler_id == null) ? '': $material->exampler->name,
                'type_scrap' => ($material->type_scrap_id == null) ? '': $material->typeScrap->name,
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

    public function getJsonMaterialsEntry()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('enable_status', 1)
            ->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'unit' => $material->unitMeasure->name, 'code' => $material->code, 'price'=>$material->unit_price, 'typescrap'=>$material->typescrap_id, 'full_typescrap'=>$material->typeScrap, 'stock_current'=>$material->stock_current, 'category'=>$material->category_id]);
        }

        //dd($materials);
        return $array;
    }

    public function getJsonMaterials()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('enable_status', 1)
            ->where('category_id', '<>', 8)->get();

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

    public function getItemsMaterialActivo($id)
    {

        $material = Material::find($id);
        //$items = Item::where('material_id', $id)->get();
        //return view('material.items', compact('items', 'material'));
        return view('material.itemsActivos', compact('material'));

    }

    public function getItemsMaterialAllActivos($id)
    {
        $material = Material::find($id);

        $arrayItems = [];

        if ( $material->category_id == 8 )
        {
            $items = Item::where('material_id', $id)
                ->with(['location' => function ($query) {
                    $query->with(['area', 'warehouse', 'shelf', 'level', 'container', 'position']);
                }])
                ->with('material')
                ->with('typescrap')
                ->with('detailEntry')->get();
        } else {
            $items = Item::where('material_id', $id)
                ->where('type', 1)
                ->with(['location' => function ($query) {
                    $query->with(['area', 'warehouse', 'shelf', 'level', 'container', 'position']);
                }])
                ->with('material')
                ->with('typescrap')
                ->with('detailEntry')->get();
        }


        //dd(datatables($items)->toJson());
        return datatables($items)->toJson();

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

    public function updateDescriptionLargeMaterials()
    {
        $begin = microtime(true);
        dump("Iniciano proceso");
        $materials = Material::all();

        foreach ($materials as $material) {
            $nombreCompleto = $material->full_description;
            $material->full_name = $nombreCompleto;
            $material->save();
        }
        $end = microtime(true) - $begin;
        dump($end);
        dd();
    }
}
