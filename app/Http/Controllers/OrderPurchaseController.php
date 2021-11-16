<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderPurchaseRequest;
use App\MaterialOrder;
use App\OrderPurchase;
use App\Quote;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderPurchaseController extends Controller
{
    public function index()
    {
        //
    }

    public function createOrderPurchaseExpress()
    {
        $quotesRaised = Quote::where('raise_status', 1)->with('equipments')->get();

        $suppliers = Supplier::all();

        $maxId = OrderPurchase::max('id')+1;
        $length = 5;
        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);


        $materials = [];
        $materials_quantity = [];

        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->materials as $material )
                {
                    array_push($materials, $material->material_id);
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity));

                }

            }
        }

        $new_arr = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials_quantity = array_values($new_arr);

        $array_materials = [];

        foreach ( $materials_quantity as $item )
        {
            if ( $item['material_complete']->stock_current < $item['quantity'] )
            {
                $material_missing = MaterialOrder::where('material_id', $item['material_id'])->first();
                $missing = (float)$item['quantity'] - (float)$item['material_complete']->stock_current;
                if ( !isset($material_missing) )
                {
                    array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing));
                } else {
                    if ( $missing > $material_missing->quantity_request )
                    {
                        $missing_real = $missing - $material_missing->quantity_request;
                        array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));
                    }
                }

            }
        }

        //dump($materials_quantity);
        /*foreach ( $array_materials as $material )
        {
            dump($material['material_id']);
        }*/
        //dump($array_materials);

        return view('orderPurchase.createExpress', compact('codeOrder', 'suppliers', 'array_materials'));
    }

    public function store(StoreOrderPurchaseRequest $request)
    {
        $validated = $request->validated();

        /*DB::beginTransaction();
        try {
            $orderPurchase = OrderPurchase::create([
                'code' => $request->get('code_quote'),
                'description_quote' => $request->get('code_description'),
                'date_quote' => ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now(),
                'date_validate' => ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now()->addDays(5),
                'way_to_pay' => ($request->has('way_to_pay')) ? $request->get('way_to_pay') : '',
                'delivery_time' => ($request->has('delivery_time')) ? $request->get('delivery_time') : '',
                'customer_id' => ($request->has('customer_id')) ? $request->get('customer_id') : null,
                'state' => 'created',
                'utility' => ($request->has('utility')) ? $request->get('utility'): 0,
                'letter' => ($request->has('letter')) ? $request->get('letter'): 0,
                'rent' => ($request->has('taxes')) ? $request->get('taxes'): 0,
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
        return response()->json(['message' => 'Cotización guardada con éxito.'], 200);*/

    }

    public function show(OrderPurchase $orderPurchase)
    {
        //
    }

    public function edit(OrderPurchase $orderPurchase)
    {
        //
    }

    public function update(Request $request, OrderPurchase $orderPurchase)
    {
        //
    }

    public function destroy(OrderPurchase $orderPurchase)
    {
        //
    }
}
