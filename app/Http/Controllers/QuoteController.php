<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Equipment;
use App\EquipmentConsumable;
use App\EquipmentMaterial;
use App\EquipmentTurnstile;
use App\EquipmentWorkforce;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Material;
use App\Quote;
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
        $unitMeasures = UnitMeasure::all();
        $customers = Customer::all();
        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();
        $workforces = Workforce::with('unitMeasure')->get();
        $maxId = Quote::max('id')+1;
        $length = 5;
        $codeQuote = 'COT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        return view('quote.create', compact('customers', 'unitMeasures', 'consumables', 'workforces', 'codeQuote'));
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
                'date_quote' => Carbon::createFromFormat('d/m/Y', $request->get('date_quote')),
                'date_validate' => Carbon::createFromFormat('d/m/Y', $request->get('date_validate')),
                'way_to_pay' => $request->get('way_to_pay'),
                'delivery_time' => $request->get('delivery_time'),
                'customer_id' => $request->get('customer_id'),
                'state' => 'created',
                'utility' => $request->get('utility'),
                'letter' => $request->get('letter'),
                'rent' => $request->get('taxes')
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

                $materials = $equipments[$i]->materials;

                $consumables = $equipments[$i]->consumables;

                $workforces = $equipments[$i]->workforces;

                $tornos = $equipments[$i]->tornos;

                for ( $j=0; $j<sizeof($materials); $j++ )
                {
                    $equipmentMaterial = EquipmentMaterial::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $materials[$j]->material->id,
                        'quantity' => (float) $materials[$j]->material_quantity,
                        'price' => (float) $materials[$j]->material->unit_price,
                        'length' => (float) ($materials[$j]->material_length == '') ? 0: $materials[$j]->material_length,
                        'width' => (float) ($materials[$j]->material_width == '') ? 0: $materials[$j]->material_width,
                        'percentage' => (float) $materials[$j]->material_quantity,
                        'state' => ($materials[$j]->material_quantity > $materials[$j]->material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ($materials[$j]->material_quantity > $materials[$j]->material->stock_current) ? 'Agotado':'Completo',
                        'total' => (float) $materials[$j]->material_price,
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

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos) * (float)$equipment->quantity;;

                $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos)* (float)$equipment->quantity;

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
        return view('quote.edit', compact('quote', 'unitMeasures', 'customers', 'consumables', 'workforces'));

    }

    public function update(UpdateQuoteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $quote = Quote::find($request->get('quote_id'));

            $quote->code = $request->get('code_quote');
            $quote->description_quote = $request->get('code_description');
            $quote->date_quote = Carbon::createFromFormat('d/m/Y', $request->get('date_quote'));
            $quote->date_validate = Carbon::createFromFormat('d/m/Y', $request->get('date_validate'));
            $quote->way_to_pay = $request->get('way_to_pay');
            $quote->delivery_time = $request->get('delivery_time');
            $quote->customer_id = $request->get('customer_id');
            $quote->utility = $request->get('utility');
            $quote->letter = $request->get('letter');
            $quote->rent = $request->get('taxes');
            $quote->save();

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

                $materials = $equipments[$i]->materials;

                $consumables = $equipments[$i]->consumables;

                $workforces = $equipments[$i]->workforces;

                $tornos = $equipments[$i]->tornos;

                for ( $j=0; $j<sizeof($materials); $j++ )
                {
                    $equipmentMaterial = EquipmentMaterial::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $materials[$j]->material->id,
                        'quantity' => (float) $materials[$j]->material_quantity,
                        'price' => (float) $materials[$j]->material->unit_price,
                        'length' => (float) ($materials[$j]->material_length == '') ? 0: $materials[$j]->material_length,
                        'width' => (float) ($materials[$j]->material_width == '') ? 0: $materials[$j]->material_width,
                        'percentage' => (float) $materials[$j]->material_quantity,
                        'state' => ($materials[$j]->material_quantity > $materials[$j]->material->stock_current) ? 'Falta comprar':'En compra',
                        'availability' => ($materials[$j]->material_quantity > $materials[$j]->material->stock_current) ? 'Agotado':'Completo',
                        'total' => (float) $materials[$j]->material_price,
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
                    $equipmentTornos = EquipmentTurnstile::create([
                        'equipment_id' => $equipment->id,
                        'description' => $tornos[$r]->description,
                        'price' => (float) $tornos[$r]->price,
                        'quantity' => (float) $tornos[$r]->quantity,
                        'total' => (float) $tornos[$r]->total
                    ]);

                    $totalTornos += $equipmentTornos->total;
                }

                $totalQuote += ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos) * (float)$equipment->quantity;;

                $equipment->total = ($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos)* (float)$equipment->quantity;

                $equipment->save();
            }

            $quote->total += $totalQuote;

            $quote->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cotización modificada con éxito.'], 200);

    }

    public function destroy(Quote $quote)
    {
        $quote->state = 'canceled';
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

        $page = $request->get('page');
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
        dump($results);
        //return response()->json($results);

    }

    public function getConsumables()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')
            ->where('category_id', 2)->get();
        return $materials;
    }

    public function getAllQuotes()
    {
        $quotes = Quote::with('customer')->get();
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
}
