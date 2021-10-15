<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Equipment;
use App\EquipmentConsumable;
use App\EquipmentMaterial;
use App\EquipmentTurnstile;
use App\EquipmentWorkday;
use App\EquipmentWorkforce;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Material;
use App\Quote;
use App\QuoteUser;
use App\UnitMeasure;
use App\Workforce;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['customer'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('quote.index', compact('quotes', 'permissions'));
    }

    public function create()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $unitMeasures = UnitMeasure::all();
        $customers = Customer::all();
        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();
        $workforces = Workforce::with('unitMeasure')->get();
        $maxId = Quote::max('id')+1;
        $length = 5;
        $codeQuote = 'COT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        return view('quote.create', compact('customers', 'unitMeasures', 'consumables', 'workforces', 'codeQuote', 'permissions'));
    }

    public function store(StoreQuoteRequest $request)
    {
        //dd($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $quote = Quote::create([
                'code' => $request->get('code_quote'),
                'description_quote' => $request->get('code_description'),
                'date_quote' => ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now(),
                'date_validate' => ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now(),
                'way_to_pay' => ($request->has('way_to_pay')) ? $request->get('way_to_pay') : '',
                'delivery_time' => ($request->has('delivery_time')) ? $request->get('delivery_time') : '',
                'customer_id' => ($request->has('customer_id')) ? $request->get('customer_id') : null,
                'state' => 'created',
                'utility' => ($request->has('utility')) ? $request->has('utility'): 0,
                'letter' => ($request->has('letter')) ? $request->get('letter'): 0,
                'rent' => ($request->has('taxes')) ? $request->get('taxes'): 0,
            ]);

            QuoteUser::create([
                'quote_id' => $quote->id,
                'user_id' => Auth::user()->id,
            ]);

            $equipments = json_decode($request->get('equipments'));

            $totalQuote = 0;

            for ( $i=0; $i<sizeof($equipments); $i++ )
            {
                $equipment = Equipment::create([
                    'quote_id' => $quote->id,
                    'description' => $equipments[$i]->description,
                    'detail' => $equipments[$i]->detail,
                    'quantity' => $equipments[$i]->quantity
                ]);

                $totalMaterial = 0;

                $totalConsumable = 0;

                $totalWorkforces = 0;

                $totalTornos = 0;

                $totalDias = 0;

                $materials = $equipments[$i]->materials;

                $consumables = $equipments[$i]->consumables;

                $workforces = $equipments[$i]->workforces;

                $tornos = $equipments[$i]->tornos;

                $dias = $equipments[$i]->dias;

                for ( $j=0; $j<sizeof($materials); $j++ )
                {
                    $equipmentMaterial = EquipmentMaterial::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $materials[$j]->material->id,
                        'quantity' => (float) $materials[$j]->quantity,
                        'price' => (float) $materials[$j]->material->unit_price,
                        'length' => (float) ($materials[$j]->length == '') ? 0: $materials[$j]->length,
                        'width' => (float) ($materials[$j]->width == '') ? 0: $materials[$j]->width,
                        'percentage' => (float) $materials[$j]->quantity,
                        'state' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Agotado':'Completo',
                        'total' => (float) $materials[$j]->price,
                    ]);

                    $totalMaterial += $equipmentMaterial->total;
                }

                for ( $k=0; $k<sizeof($consumables); $k++ )
                {
                    $material = Material::find($consumables[$k]->id);

                    $equipmentConsumable = EquipmentConsumable::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $consumables[$k]->id,
                        'quantity' => (float) $consumables[$k]->quantity,
                        'price' => (float) $consumables[$k]->price,
                        'total' => (float) $consumables[$k]->total,
                        'state' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Agotado':'Completo',
                    ]);

                    $totalConsumable += $equipmentConsumable->total;
                }

                for ( $w=0; $w<sizeof($workforces); $w++ )
                {
                    $equipmentWorkforce = EquipmentWorkforce::create([
                        'equipment_id' => $equipment->id,
                        'description' => $workforces[$w]->description,
                        'price' => (float) $workforces[$w]->price,
                        'quantity' => (float) $workforces[$w]->quantity,
                        'total' => (float) $workforces[$w]->total,
                        'unit' => $workforces[$w]->unit,
                    ]);

                    $totalWorkforces += $equipmentWorkforce->total;
                }

                for ( $r=0; $r<sizeof($tornos); $r++ )
                {
                    $equipmenttornos = EquipmentTurnstile::create([
                        'equipment_id' => $equipment->id,
                        'description' => $tornos[$r]->description,
                        'price' => (float) $tornos[$r]->price,
                        'quantity' => (float) $tornos[$r]->quantity,
                        'total' => (float) $tornos[$r]->total
                    ]);

                    $totalTornos += $equipmenttornos->total;
                }

                for ( $d=0; $d<sizeof($dias); $d++ )
                {
                    $equipmentdias = EquipmentWorkday::create([
                        'equipment_id' => $equipment->id,
                        'quantityPerson' => (float) $dias[$d]->quantity,
                        'hoursPerPerson' => (float) $dias[$d]->hours,
                        'pricePerHour' => (float) $dias[$d]->price,
                        'total' => (float) $dias[$d]->total
                    ]);

                    $totalDias += $equipmentdias->total;
                }

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$equipment->quantity;;

                $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias)* (float)$equipment->quantity;

                $equipment->save();
            }

            $quote->total = $totalQuote;

            $quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cotización guardada con éxito.'], 200);

    }

    public function show($id)
    {
        $unitMeasures = UnitMeasure::all();
        $customers = Customer::all();
        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();
        $workforces = Workforce::with('unitMeasure')->get();

        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles']);
            }])->first();
        //dump($quote);
        return view('quote.show', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $unitMeasures = UnitMeasure::all();
        $customers = Customer::all();
        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();
        $workforces = Workforce::with('unitMeasure')->get();

        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();
        //dump($quote);
        return view('quote.edit', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces', 'permissions'));

    }

    public function update(UpdateQuoteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $quote = Quote::find($request->get('quote_id'));

            $quote->code = $request->get('code_quote');
            $quote->description_quote = $request->get('code_description');
            $quote->date_quote = ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now();
            $quote->date_validate = ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now();
            $quote->way_to_pay = ($request->has('way_to_pay')) ? $request->get('way_to_pay') : '';
            $quote->delivery_time = ($request->has('delivery_time')) ? $request->get('delivery_time') : '';
            $quote->customer_id = ($request->has('customer_id')) ? $request->get('customer_id') : null;
            $quote->utility = ($request->has('utility')) ? $request->has('utility'): 0;
            $quote->letter = ($request->has('letter')) ? $request->get('letter'): 0;
            $quote->rent = ($request->has('taxes')) ? $request->get('taxes'): 0;
            $quote->save();

            $equipments = json_decode($request->get('equipments'));

            $totalQuote = 0;

            for ( $i=0; $i<sizeof($equipments); $i++ )
            {
                if ($equipments[$i]->quote === '' )
                {
                    $equipment = Equipment::create([
                        'quote_id' => $quote->id,
                        'description' => $equipments[$i]->description,
                        'detail' => $equipments[$i]->detail,
                        'quantity' => $equipments[$i]->quantity
                    ]);

                    $totalMaterial = 0;

                    $totalConsumable = 0;

                    $totalWorkforces = 0;

                    $totalTornos = 0;

                    $totalDias = 0;

                    $materials = $equipments[$i]->materials;

                    $consumables = $equipments[$i]->consumables;

                    $workforces = $equipments[$i]->workforces;

                    $tornos = $equipments[$i]->tornos;

                    $dias = $equipments[$i]->dias;

                    for ( $j=0; $j<sizeof($materials); $j++ )
                    {
                        $equipmentMaterial = EquipmentMaterial::create([
                            'equipment_id' => $equipment->id,
                            'material_id' => $materials[$j]->material->id,
                            'quantity' => (float) $materials[$j]->quantity,
                            'price' => (float) $materials[$j]->material->unit_price,
                            'length' => (float) ($materials[$j]->length == '') ? 0: $materials[$j]->length,
                            'width' => (float) ($materials[$j]->width == '') ? 0: $materials[$j]->width,
                            'percentage' => (float) $materials[$j]->quantity,
                            'state' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Falta comprar':'En compra',
                            'availability' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Agotado':'Completo',
                            'total' => (float) $materials[$j]->price,
                        ]);

                        $totalMaterial += $equipmentMaterial->total;
                    }

                    for ( $k=0; $k<sizeof($consumables); $k++ )
                    {
                        $material = Material::find($consumables[$k]->id);

                        $equipmentConsumable = EquipmentConsumable::create([
                            'equipment_id' => $equipment->id,
                            'material_id' => $consumables[$k]->id,
                            'quantity' => (float) $consumables[$k]->quantity,
                            'price' => (float) $consumables[$k]->price,
                            'total' => (float) $consumables[$k]->total,
                            'state' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Falta comprar':'En compra',
                            'availability' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Agotado':'Completo',
                        ]);

                        $totalConsumable += $equipmentConsumable->total;
                    }

                    for ( $w=0; $w<sizeof($workforces); $w++ )
                    {
                        $equipmentWorkforce = EquipmentWorkforce::create([
                            'equipment_id' => $equipment->id,
                            'description' => $workforces[$w]->description,
                            'price' => (float) $workforces[$w]->price,
                            'quantity' => (float) $workforces[$w]->quantity,
                            'total' => (float) $workforces[$w]->total,
                            'unit' => $workforces[$w]->unit,
                        ]);

                        $totalWorkforces += $equipmentWorkforce->total;
                    }

                    for ( $r=0; $r<sizeof($tornos); $r++ )
                    {
                        $equipmenttornos = EquipmentTurnstile::create([
                            'equipment_id' => $equipment->id,
                            'description' => $tornos[$r]->description,
                            'price' => (float) $tornos[$r]->price,
                            'quantity' => (float) $tornos[$r]->quantity,
                            'total' => (float) $tornos[$r]->total
                        ]);

                        $totalTornos += $equipmenttornos->total;
                    }

                    for ( $d=0; $d<sizeof($dias); $d++ )
                    {
                        $equipmentdias = EquipmentWorkday::create([
                            'equipment_id' => $equipment->id,
                            'quantityPerson' => (float) $dias[$d]->quantity,
                            'hoursPerPerson' => (float) $dias[$d]->hours,
                            'pricePerHour' => (float) $dias[$d]->price,
                            'total' => (float) $dias[$d]->total
                        ]);

                        $totalDias += $equipmentdias->total;
                    }

                    $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$equipment->quantity;;

                    $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias)* (float)$equipment->quantity;

                    $equipment->save();
                }

            }

            $quote->total += $totalQuote;

            $quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Nuevos equipos guardados con éxito.'], 200);

    }

    public function destroy(Quote $quote)
    {
        $quote->state = 'canceled';
        $quote->save();
    }

    public function confirm(Quote $quote)
    {
        $quote->state = 'confirmed';
        $quote->save();
    }

    public function selectMaterials(Request $request)
    {
        /*$page = $request->get('page');

        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        $search = $request->get('term');

        //$materials = Material::where('description', 'LIKE',  '%' . $search . '%')->orderBy('description')->skip($offset)->take($resultCount)->get(['id','description']);
        $materials = Material::skip($offset)->take($resultCount)->get()->filter(function ($item) use ($search) {
            // replace stristr with your choice of matching function
            return stripos($item->full_description, $search) === false ? false : true;

        });

        //dump($materials[0]->name_product);
        $count = Count(Material::get()->filter(function ($item) use ($search) {
            // replace stristr with your choice of matching function
            return stripos($item->full_description, $search) === false ? false : true;

        }));
        //dump($count);
        $endCount = $offset + $resultCount;
        //dump($endCount);
        $morePages = $count > $endCount;

        $results = array(
            "results" => $materials,
            "pagination" => array(
                "more" => $morePages
            )
        );
        //dump($results);
        return response()->json($results);*/
        $materials = [];

        if($request->has('q')){
            $search = $request->get('q');
            $materials = Material::get()->filter(function ($item) use ($search) {
                // replace stristr with your choice of matching function
                return false !== stristr($item->full_description, $search);

            });
        }
        return json_encode($materials);


    }

    public function getMaterials()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')->get();
        return $materials;
    }

    public function getMaterialsTypeahead()
    {
        $materials = Material::all();
        return $materials;
    }

    public function selectConsumables(Request $request)
    {

        /*$page = $request->get('page');
        dump($page);
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        $search = $request->get('term');
        $materials = Material::where('category_id', 2)->get()->filter(function ($item) use ($search) {
            // replace stristr with your choice of matching function
            return false !== stristr($item->full_description, $search);
        });
        dump($materials);
        $count = Count($materials);
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = array(
            "results" => $materials,
            "pagination" => array(
                "more" => $morePages
            )
        );
        dump($results);*/
        //return response()->json($results);
        $materials = [];

        if($request->has('q')){
            $search = $request->get('q');
            $materials = Material::where('category_id', 2)->get()->filter(function ($item) use ($search) {
                // replace stristr with your choice of matching function
                return false !== stristr($item->full_description, $search);
            });
        }
        return json_encode($materials);
    }

    public function getConsumables()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('category_id', 2)->get();
        return $materials;
    }

    public function getAllQuotes()
    {
        $quotes = Quote::with('customer')
            ->where('raise_status',false)->get();
        return datatables($quotes)->toJson();
    }

    public function printQuoteToCustomer($id)
    {
        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles']);
            }])->first();

        $view = view('exports.quoteCustomer', compact('quote'));

        $pdf = PDF::loadHTML($view);

        $name = $quote->code . '.pdf';

        return $pdf->stream($name);
    }

    public function printQuoteToInternal($id)
    {
        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles']);
            }])->first();

        $view = view('exports.quoteInternal', compact('quote'));

        $pdf = PDF::loadHTML($view);

        $name = $quote->code . '.pdf';

        return $pdf->stream($name);
    }

    public function destroyEquipmentOfQuote($id_equipment, $id_quote)
    {
        $user = Auth::user();
        $quote = Quote::find($id_quote);
        $quote_user = QuoteUser::where('quote_id', $id_quote)
            ->where('user_id', $user->id)->first();
        if ( !$quote_user ) {
            return response()->json(['message' => 'No puede eliminar un equipo que no es de su propiedad'], 422);
        }

        DB::beginTransaction();
        try {
            $equipment_quote = Equipment::where('id', $id_equipment)
                ->where('quote_id',$quote->id)->first();

            foreach( $equipment_quote->materials as $material ) {
                $material->delete();
            }
            foreach( $equipment_quote->consumables as $consumable ) {
                $consumable->delete();
            }
            foreach( $equipment_quote->workforces as $workforce ) {
                $workforce->delete();
            }
            foreach( $equipment_quote->turnstiles as $turnstile ) {
                $turnstile->delete();
            }
            foreach( $equipment_quote->workdays as $workday ) {
                $workday->delete();
            }

            $equipment_quote->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Equipo eliminada con éxito.'], 200);

    }

    public function updateEquipmentOfQuote(Request $request, $id_equipment, $id_quote)
    {
        //dump($request);
        $user = Auth::user();
        $quote = Quote::find($id_quote);
        $quote_user = QuoteUser::where('quote_id', $id_quote)
            ->where('user_id', $user->id)->first();
        if ( !$quote_user ) {
            return response()->json(['message' => 'No puede eliminar un equipo que no es de su propiedad'], 422);
        }

        $equipmentSent = null;

        DB::beginTransaction();
        try {
            $equipment_quote = Equipment::where('id', $id_equipment)
                ->where('quote_id',$quote->id)->first();

            $totalDeleted = 0;
            foreach( $equipment_quote->materials as $material ) {
                $totalDeleted = $totalDeleted + (float) $material->total;
                $material->delete();
            }
            foreach( $equipment_quote->consumables as $consumable ) {
                $totalDeleted = $totalDeleted + (float) $consumable->total;
                $consumable->delete();
            }
            foreach( $equipment_quote->workforces as $workforce ) {
                $totalDeleted = $totalDeleted + (float) $workforce->total;
                $workforce->delete();
            }
            foreach( $equipment_quote->turnstiles as $turnstile ) {
                $totalDeleted = $totalDeleted + (float) $turnstile->total;
                $turnstile->delete();
            }
            foreach( $equipment_quote->workdays as $workday ) {
                $totalDeleted = $totalDeleted + (float) $workday->total;
                $workday->delete();
            }

            $totalDeleted = $totalDeleted * (float) $equipment_quote->quantity;

            $quote->total = $quote->total - $totalDeleted;
            $quote->save();

            $equipment_quote->delete();

            $equipments = $request->input('equipment');

            $totalQuote = 0;

            foreach ( $equipments as $equip )
            {
                //dump($equip['quantity']);
                $equipment = Equipment::create([
                    'quote_id' => $quote->id,
                    'description' => $equip['description'],
                    'detail' => $equip['detail'],
                    'quantity' => $equip['quantity']
                ]);

                $totalMaterial = 0;

                $totalConsumable = 0;

                $totalWorkforces = 0;

                $totalTornos = 0;

                $totalDias = 0;

                $materials = $equip['materials'];

                $consumables = $equip['consumables'];

                $workforces = $equip['workforces'];

                $tornos = $equip['tornos'];

                $dias = $equip['dias'];
                //dump($materials);
                foreach ( $materials as $material )
                {
                    //dump($material['material']['quantity']);
                    $equipmentMaterial = EquipmentMaterial::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $material['material']['id'],
                        'quantity' => (float) $material['quantity'],
                        'price' => (float) $material['material']['unit_price'],
                        'length' => (float) ($material['length'] == '') ? 0: $material['length'],
                        'width' => (float) ($material['width'] == '') ? 0: $material['width'],
                        'percentage' => (float) $material['quantity'],
                        'state' => ($material['quantity'] > $material['material']['stock_current']) ? 'Falta comprar':'En compra',
                        'availability' => ($material['quantity'] > $material['material']['stock_current']) ? 'Agotado':'Completo',
                        'total' => (float) $material['price'],
                    ]);

                    $totalMaterial += $equipmentMaterial->total;
                }

                foreach ( $consumables as $consumable )
                {
                    //dump($consumable['id']);
                    $material = Material::find($consumable['id']);
                    //dump($material);
                    $equipmentConsumable = EquipmentConsumable::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $consumable['id'],
                        'quantity' => (float) $consumable['quantity'],
                        'price' => (float) $consumable['price'],
                        'total' => (float) $consumable['total'],
                        'state' => ((float) $consumable['quantity'] > $material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ((float) $consumable['quantity'] > $material->stock_current) ? 'Agotado':'Completo',
                    ]);

                    $totalConsumable += $equipmentConsumable->total;
                }

                foreach ( $workforces as $workforce )
                {
                    $equipmentWorkforce = EquipmentWorkforce::create([
                        'equipment_id' => $equipment->id,
                        'description' => $workforce['description'],
                        'price' => (float) $workforce['price'],
                        'quantity' => (float) $workforce['quantity'],
                        'total' => (float) $workforce['total'],
                        'unit' => $workforce['unit'],
                    ]);

                    $totalWorkforces += $equipmentWorkforce->total;
                }

                foreach ( $tornos as $torno )
                {
                    $equipmenttornos = EquipmentTurnstile::create([
                        'equipment_id' => $equipment->id,
                        'description' => $torno['description'],
                        'price' => (float) $torno['price'],
                        'quantity' => (float) $torno['quantity'],
                        'total' => (float) $torno['total']
                    ]);

                    $totalTornos += $equipmenttornos->total;
                }

                foreach ( $dias as $dia )
                {
                    $equipmentdias = EquipmentWorkday::create([
                        'equipment_id' => $equipment->id,
                        'quantityPerson' => (float) $dia['quantity'],
                        'hoursPerPerson' => (float) $dia['hours'],
                        'pricePerHour' => (float) $dia['price'],
                        'total' => (float) $dia['total']
                    ]);

                    $totalDias += $equipmentdias->total;
                }

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$equipment->quantity;;

                $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias)* (float)$equipment->quantity;

                $equipment->save();

                $equipmentSent = $equipment;

            }

            $quote->total += $totalQuote;

            $quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Equipo guardado con éxito.', 'equipment'=>$equipmentSent, 'quote'=>$quote], 200);

    }

    public function raise()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('quote.raise', compact( 'permissions'));
    }

    public function raiseQuote($quote_id, $code)
    {
        $quote = Quote::find($quote_id);
        $quote->code_customer = $code;
        $quote->raise_status = true;
        $quote->save();
    }

    public function getAllQuotesConfirmed()
    {
        $quotes = Quote::with(['customer'])
            ->where('state','confirmed')
            ->get();
        return datatables($quotes)->toJson();
    }
}
