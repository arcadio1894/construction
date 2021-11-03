<?php

namespace App\Http\Controllers;

use App\MaterialOrder;
use App\OrderPurchase;
use App\Quote;
use Illuminate\Http\Request;

class OrderPurchaseController extends Controller
{
    public function index()
    {
        //
    }

    public function createOrderPurchaseExpress()
    {
        $quotesRaised = Quote::where('raise_status', 1)->with('equipments')->get();

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
        foreach ( $array_materials as $material )
        {
            dump($material['material_id']);
        }
        //dump($array_materials);

        //return view('orderPurchase.createExpress', compact('array_materials'));
    }

    public function store(Request $request)
    {
        //
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
