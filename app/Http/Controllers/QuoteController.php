<?php

namespace App\Http\Controllers;

use App\ContactName;
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
use App\MaterialTaken;
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
        //dump($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $quote = Quote::create([
                'code' => $request->get('code_quote'),
                'description_quote' => $request->get('code_description'),
                'date_quote' => ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now(),
                'date_validate' => ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now()->addDays(5),
                'way_to_pay' => ($request->has('way_to_pay')) ? $request->get('way_to_pay') : '',
                'delivery_time' => ($request->has('delivery_time')) ? $request->get('delivery_time') : '',
                'customer_id' => ($request->has('customer_id')) ? $request->get('customer_id') : null,
                'contact_id' => ($request->has('contact_id')) ? $request->get('contact_id') : null,
                'state' => 'created',
                'utility' => ($request->has('utility')) ? $request->get('utility'): 0,
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
                        'total' => (float) $materials[$j]->quantity*(float) $materials[$j]->material->unit_price,
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
                        'description' => $dias[$d]->description,
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
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
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

        $quote3 = Quote::where('id', $id)
            ->with('customer')
            ->with('contact')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();

        if ( $quote3->state === 'created' )
        {
            foreach( $quote3->equipments as $equipment )
            {
                // TODO: Actualizar los precios
                foreach ( $equipment->materials as $equipment_material )
                {
                    if ( $equipment_material->price !== $equipment_material->material->unit_price )
                    {
                        $equipment_material->price = $equipment_material->material->unit_price;
                        $equipment_material->total = $equipment_material->material->unit_price * $equipment_material->quantity;
                        $equipment_material->save();
                    }
                }

                foreach ( $equipment->consumables as $equipment_consumable )
                {
                    if ( $equipment_consumable->price !== $equipment_consumable->material->unit_price )
                    {
                        $equipment_consumable->price = $equipment_consumable->material->unit_price;
                        $equipment_consumable->total = $equipment_consumable->material->unit_price * $equipment_consumable->quantity;
                        $equipment_consumable->save();
                    }
                }
            }

            $quote2 = Quote::where('id', $id)
                ->with('customer')
                ->with(['equipments' => function ($query) {
                    $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
                }])->first();

            $new_total_quote = 0;
            foreach( $quote2->equipments as $equipment )
            {
                $new_total_material = 0;
                foreach ( $equipment->materials as $equipment_material )
                {
                    $new_total_material = $new_total_material + $equipment_material->total;
                }
                $new_total_consumable = 0;
                foreach ( $equipment->consumables as $equipment_consumable )
                {
                    $new_total_consumable = $new_total_consumable + $equipment_consumable->total;
                }
                $new_total_workforce = 0;
                foreach ( $equipment->workforces as $equipment_workforce )
                {
                    $new_total_workforce = $new_total_workforce + $equipment_workforce->total;
                }
                $new_total_turnstile = 0;
                foreach ( $equipment->turnstiles as $equipment_turnstile )
                {
                    $new_total_turnstile = $new_total_turnstile + $equipment_turnstile->total;
                }
                $new_total_workday = 0;
                foreach ( $equipment->workdays as $equipment_workday )
                {
                    $new_total_workday = $new_total_workday + $equipment_workday->total;
                }
                $new_total_quote = $new_total_quote + (($new_total_material + $new_total_consumable + $new_total_workforce + $new_total_turnstile + $new_total_workday) * $equipment->quantity);
                $equipment->total = ($new_total_material + $new_total_consumable + $new_total_workforce + $new_total_turnstile + $new_total_workday) * $equipment->quantity;
                $equipment->save();
            }
            $quote2->total = $new_total_quote ;
            $quote2->save();
        }

        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();

        //dump($quote);
        return view('quote.edit', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces', 'permissions'));

    }

    public function adjust($id)
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
            ->with('contact')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();

        //dump($quote);
        return view('quote.adjust', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces', 'permissions'));

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
            $quote->date_validate = ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now()->addDays(5);
            $quote->way_to_pay = ($request->has('way_to_pay')) ? $request->get('way_to_pay') : '';
            $quote->delivery_time = ($request->has('delivery_time')) ? $request->get('delivery_time') : '';
            $quote->customer_id = ($request->has('customer_id')) ? $request->get('customer_id') : null;
            $quote->contact_id = ($request->has('contact_id')) ? $request->get('contact_id') : null;
            $quote->utility = ($request->has('utility')) ? $request->get('utility'): 0;
            $quote->letter = ($request->has('letter')) ? $request->get('letter'): 0;
            $quote->rent = ($request->has('taxes')) ? $request->get('taxes'): 0;
            $quote->currency_invoice = 'USD';
            $quote->currency_compra = null;
            $quote->currency_venta = null;
            $quote->total_soles = 0;
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
                            'total' => (float) $materials[$j]->material->unit_price*(float) $materials[$j]->quantity,
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
                            'total' => (float) $consumables[$k]->quantity*(float) $consumables[$k]->price,
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
                            'total' => (float) $workforces[$w]->price*(float) $workforces[$w]->quantity,
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
                            'total' => (float) $tornos[$r]->price*(float) $tornos[$r]->quantity
                        ]);

                        $totalTornos += $equipmenttornos->total;
                    }

                    for ( $d=0; $d<sizeof($dias); $d++ )
                    {
                        $equipmentdias = EquipmentWorkday::create([
                            'equipment_id' => $equipment->id,
                            'description' => $dias[$d]->description,
                            'quantityPerson' => (float) $dias[$d]->quantity,
                            'hoursPerPerson' => (float) $dias[$d]->hours,
                            'pricePerHour' => (float) $dias[$d]->price,
                            'total' => (float) $dias[$d]->quantity*(float) $dias[$d]->hours*(float) $dias[$d]->price
                        ]);

                        $totalDias += $equipmentdias->total;
                    }

                    $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$equipment->quantity;

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
            ->where('raise_status', 0)
            ->whereNotIn('state', ['canceled', 'expired'])
            ->where('state_active', 'open')
            ->get();
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

            $quote->total -= $equipment_quote->total;
            $quote->currency_invoice = 'USD';
            $quote->currency_compra = null;
            $quote->currency_venta = null;
            $quote->total_soles = 0;
            $quote->save();

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
        if ( !$quote_user && !$user->hasRole(['admin', 'logistic']) ) {
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
                    //dump($material['material']['stock_current']);
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
                        'total' => (float) $material['quantity']*(float) $material['material']['unit_price']
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
                        'total' => (float) $consumable['quantity']*(float) $consumable['price'],
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
                        'total' => (float) $workforce['price']*(float) $workforce['quantity'],
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
                        'total' => (float) $torno['price']*(float) $torno['quantity']
                    ]);

                    $totalTornos += $equipmenttornos->total;
                }

                foreach ( $dias as $dia )
                {
                    $equipmentdias = EquipmentWorkday::create([
                        'equipment_id' => $equipment->id,
                        'description' => $dia['description'],
                        'quantityPerson' => (float) $dia['quantity'],
                        'hoursPerPerson' => (float) $dia['hours'],
                        'pricePerHour' => (float) $dia['price'],
                        'total' => (float) $dia['quantity']*(float) $dia['hours']*(float) $dia['price']
                    ]);

                    $totalDias += $equipmentdias->total;
                }

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$equipment->quantity;;

                $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias)* (float)$equipment->quantity;

                $equipment->save();

                $equipmentSent = $equipment;

            }

            $quote->total += $totalQuote;

            $quote->currency_invoice = 'USD';
            $quote->currency_compra = null;
            $quote->currency_venta = null;
            $quote->total_soles = 0;

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

        if ( !isset( $quote->order_execution ) )
        {
            $all_quotes = Quote::whereNotNull('order_execution')->get();
            $quantity = count($all_quotes) + 1;
            $length = 5;
            $codeOrderExecution = 'OE-'.str_pad($quantity,$length,"0", STR_PAD_LEFT);
            $quote->order_execution = $codeOrderExecution;
            $quote->save();
        }

        $quote->code_customer = $code;
        $quote->raise_status = true;
        $quote->save();
    }

    public function getAllQuotesConfirmed()
    {
        $quotes = Quote::with(['customer'])
            ->where('state_active','open')
            ->where('state','confirmed')
            ->get();
        return datatables($quotes)->toJson();
    }

    public function quoteInSoles($id)
    {
        $unitMeasures = UnitMeasure::all();
        $customers = Customer::all();
        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();
        $workforces = Workforce::with('unitMeasure')->get();

        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with('contact')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();
        //dump($quote);
        return view('quote.quoteInSoles', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces'));
    }

    public function saveQuoteInSoles( Quote $quote )
    {
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        //dump($request->get('date_invoice'));
        $fecha = Carbon::parse($quote->date_quote);

        //dump();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha->format('Y-m-d'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $tipoCambioSunat = json_decode($response);

        $quote->currency_invoice = 'PEN';
        $quote->currency_compra = (float) $tipoCambioSunat->compra;
        $quote->currency_venta = (float) $tipoCambioSunat->venta;
        $quote->total_soles = $quote->total * (float) $tipoCambioSunat->venta;
        $quote->save();

        return response()->json(['total' => $quote->total_soles, 'message'=>'Cotización cambiada a soles'], 200);

    }

    public function adjustQuote(Request $request)
    {
        //dump($request);
        DB::beginTransaction();
        try {
            $quote = Quote::find($request->get('quote_id'));

            $quote->utility = ($request->has('utility')) ? $request->get('utility'): 0;
            $quote->letter = ($request->has('letter')) ? $request->get('letter'): 0;
            $quote->rent = ($request->has('taxes')) ? $request->get('taxes'): 0;
            $quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ajuste de porcentajes realizado con éxito.'], 200);

    }

    public function deleted()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('quote.delete', compact( 'permissions'));
    }

    public function getAllQuotesDeleted()
    {
        $quotes = Quote::with(['customer'])
            ->whereIn('state',['canceled', 'expired'])
            ->get();
        return datatables($quotes)->toJson();
    }

    public function getAllQuotesClosed()
    {
        $quotes = Quote::with(['customer'])
            ->whereIn('state_active',['close'])
            ->get();
        return datatables($quotes)->toJson();
    }

    public function closeQuote($quote_id)
    {
        $quote = Quote::find($quote_id);

        $quote->state_active = 'close';
        $quote->save();

        DB::beginTransaction();
        try {

            $material_takens = MaterialTaken::where('quote_id', $quote_id)->get();

            foreach ( $material_takens as $material_taken )
            {
                $material_taken->delete();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cotización finalizada con éxito. Redireccionando ...', 'url'=>route('quote.closed')], 200);

    }

    public function closed()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('quote.close', compact( 'permissions'));
    }

    public function renewQuote($id)
    {
        $quote = Quote::where('id', $id)
            ->with('customer')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles', 'workdays']);
            }])->first();
        //dump($quote);

        DB::beginTransaction();
        try {
            $maxId = Quote::max('id')+1;
            $length = 5;
            $codeQuote = 'COT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $renew_quote = Quote::create([
                'code' => $codeQuote,
                'description_quote' => $quote->description_quote,
                'date_quote' => Carbon::now(),
                'date_validate' => Carbon::now()->addDays(5),
                'way_to_pay' => $quote->way_to_pay,
                'delivery_time' => $quote->delivery_time,
                'customer_id' => $quote->customer_id,
                'state' => 'created',
                'utility' => $quote->utility,
                'letter' => $quote->letter,
                'rent' => $quote->rent,
            ]);

            QuoteUser::create([
                'quote_id' => $renew_quote->id,
                'user_id' => Auth::user()->id,
            ]);

            $totalQuote = 0;

            foreach ( $quote->equipments as $equipment )
            {
                $renew_equipment = Equipment::create([
                    'quote_id' => $renew_quote->id,
                    'description' => $equipment->description,
                    'detail' => $equipment->detail,
                    'quantity' => $equipment->quantity,
                ]);

                $totalMaterial = 0;

                $totalConsumable = 0;

                $totalWorkforces = 0;

                $totalTornos = 0;

                $totalDias = 0;

                foreach ( $equipment->materials as $material )
                {
                    $renew_equipmentMaterial = EquipmentMaterial::create([
                        'equipment_id' => $renew_equipment->id,
                        'material_id' => $material->material->id,
                        'quantity' => (float) $material->quantity,
                        'price' => (float) $material->material->unit_price,
                        'length' => (float) $material->length,
                        'width' => (float) $material->width,
                        'percentage' => (float) $material->percentage,
                        'state' => ($material->quantity > $material->material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ($material->quantity > $material->material->stock_current) ? 'Agotado':'Completo',
                        'total' => (float) $material->quantity*(float) $material->material->unit_price,
                    ]);

                    $totalMaterial += $renew_equipmentMaterial->total;
                }

                foreach ( $equipment->consumables as $consumable )
                {
                    $material = Material::find($consumable->id);

                    $renew_equipmentConsumable = EquipmentConsumable::create([
                        'equipment_id' => $renew_equipment->id,
                        'material_id' => $consumable->id,
                        'quantity' => (float) $consumable->quantity,
                        'price' => (float) $material->unit_price,
                        'total' => (float) $consumable->quantity*$material->unit_price,
                        'state' => ((float) $consumable->quantity > $material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ((float) $consumable->quantity > $material->stock_current) ? 'Agotado':'Completo',
                    ]);

                    $totalConsumable += $renew_equipmentConsumable->total;
                }

                foreach ( $equipment->workforces as $workforce )
                {
                    $renew_equipmentWorkforce = EquipmentWorkforce::create([
                        'equipment_id' => $renew_equipment->id,
                        'description' => $workforce->description,
                        'price' => (float) $workforce->price,
                        'quantity' => (float) $workforce->quantity,
                        'total' => (float) $workforce->total,
                        'unit' => $workforce->unit,
                    ]);

                    $totalWorkforces += $renew_equipmentWorkforce->total;
                }

                foreach ( $equipment->turnstiles as $turnstile )
                {
                    $renew_equipmenttornos = EquipmentTurnstile::create([
                        'equipment_id' => $renew_equipment->id,
                        'description' => $turnstile->description,
                        'price' => (float) $turnstile->price,
                        'quantity' => (float) $turnstile->quantity,
                        'total' => (float) $turnstile->total
                    ]);

                    $totalTornos += $renew_equipmenttornos->total;
                }

                foreach ( $equipment->workdays as $workday )
                {
                    $renew_equipmentdias = EquipmentWorkday::create([
                        'equipment_id' => $renew_equipment->id,
                        'description' => $workday->description,
                        'quantityPerson' => (float) $workday->quantityPerson,
                        'hoursPerPerson' => (float) $workday->hoursPerPerson,
                        'pricePerHour' => (float) $workday->pricePerHour,
                        'total' => (float) $workday->total
                    ]);

                    $totalDias += $renew_equipmentdias->total;
                }

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias) * (float)$renew_equipment->quantity;;

                $renew_equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos + $totalDias)* (float)$renew_equipment->quantity;

                $renew_equipment->save();
            }

            $renew_quote->total = $totalQuote;

            $renew_quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cotización renovada con éxito. Redireccionando ...', 'url'=>route('quote.edit', $renew_quote->id)], 200);

    }

    public function getContactsByCustomer($customer_id)
    {
        $contacts = ContactName::where('customer_id', $customer_id)->get();
        $array = [];
        foreach ( $contacts as $contact )
        {
            array_push($array, ['id'=> $contact->id, 'contact' => $contact->name]);
        }

        //dd($array);
        return $array;
    }

}
