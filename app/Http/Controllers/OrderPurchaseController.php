<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderPurchaseRequest;
use App\MaterialOrder;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use App\Quote;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderPurchaseController extends Controller
{
    public function indexOrderPurchaseExpress()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexExpress', compact('permissions'));
    }

    public function createOrderPurchaseExpress()
    {
        $quotesRaised = Quote::where('raise_status', 1)->with('equipments')->get();

        $suppliers = Supplier::all();
        $users = User::all();

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
                $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request');
                $missing = (float)$item['quantity'] - (float)$item['material_complete']->stock_current;
                if ( !isset($material_missing) )
                {
                    array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing));
                } else {
                    if ( $missing > $amount )
                    {
                        $missing_real = $missing - $amount;
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

        return view('orderPurchase.createExpress', compact('users', 'codeOrder', 'suppliers', 'array_materials'));
    }

    public function storeOrderPurchaseExpress(StoreOrderPurchaseRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderPurchase = OrderPurchase::create([
                'code' => $request->get('purchase_order'),
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_arrival' => ($request->has('date_arrival')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_arrival')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('purchase_condition')) ? $request->get('purchase_condition') : '',
                'currency_order' => ($request->has('currency_order')) ? 'PEN':'USD',
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
            ]);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseDetail = OrderPurchaseDetail::create([
                    'order_purchase_id' => $orderPurchase->id,
                    'material_id' => $items[$i]->id_material,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                ]);

                $total = $orderPurchaseDetail->quantity*$orderPurchaseDetail->price;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderPurchaseDetail->igv = $igv;
                $orderPurchaseDetail->save();

                MaterialOrder::create([
                    'order_purchase_detail_id' => $orderPurchaseDetail->id,
                    'material_id' => $orderPurchaseDetail->material_id,
                    'quantity_request' => $orderPurchaseDetail->quantity,
                    'quantity_entered' => 0
                ]);
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden compra express guardada con éxito.'], 200);

    }

    public function showOrderPurchaseExpress($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.showExpress', compact('order', 'details', 'suppliers', 'users'));

    }

    public function updateOrderPurchaseExpress(StoreOrderPurchaseRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderPurchase = OrderPurchase::find($request->get('order_id'));
            $orderPurchase->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderPurchase->date_arrival = ($request->has('date_arrival')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_arrival')) : Carbon::now();
            $orderPurchase->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderPurchase->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderPurchase->payment_condition = ($request->has('purchase_condition')) ? $request->get('purchase_condition') : '';
            $orderPurchase->currency_order = ($request->get('state') === true) ? 'PEN': 'USD';
            $orderPurchase->observation = $request->get('observation');
            $orderPurchase->igv = $request->get('taxes_send');
            $orderPurchase->total = $request->get('total_send');
            $orderPurchase->save();

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                if ($items[$i]->detail_id === '')
                {
                    $orderPurchaseDetail = OrderPurchaseDetail::create([
                        'order_purchase_id' => $orderPurchase->id,
                        'material_id' => $items[$i]->id_material,
                        'quantity' => (float) $items[$i]->quantity,
                        'price' => (float) $items[$i]->price,
                    ]);

                    $total = $orderPurchaseDetail->quantity*$orderPurchaseDetail->price;
                    $subtotal = $total / 1.18;
                    $igv = $total - $subtotal;
                    $orderPurchaseDetail->igv = $igv;
                    $orderPurchaseDetail->save();

                    MaterialOrder::create([
                        'order_purchase_detail_id' => $orderPurchaseDetail->id,
                        'material_id' => $orderPurchaseDetail->material_id,
                        'quantity_request' => $orderPurchaseDetail->quantity,
                        'quantity_entered' => 0
                    ]);
                }

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden compra express modificada con éxito.'], 200);

    }

    public function editOrderPurchaseExpress($id)
    {
        $quotesRaised = Quote::where('raise_status', 1)->with('equipments')->get();

        $suppliers = Supplier::all();
        $users = User::all();

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
                $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request');
                $missing = (float)$item['quantity'] - (float)$item['material_complete']->stock_current;
                if ( !isset($material_missing) )
                {
                    array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing));
                } else {
                    if ( $missing > $amount )
                    {
                        $missing_real = $missing - $amount;
                        array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));
                    }
                }

            }
        }

        $order = OrderPurchase::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.editExpress', compact('order', 'details', 'suppliers', 'users', 'array_materials'));
    }

    public function updateDetail(Request $request, $detail_id)
    {
        $detail = OrderPurchaseDetail::find($detail_id);
        $orderExpress = OrderPurchase::find($detail->order_purchase_id);

        $items = json_decode($request->get('items'));

        for ( $i=0; $i<sizeof($items); $i++ )
        {
            $material_order = MaterialOrder::where('material_id', $items[$i]->id_material)
                ->where('order_purchase_detail_id', $detail->id)->first();
            if ( $material_order->quantity_entered > 0 ) {
                return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
            } else {
                $total_last = $detail->total;
                $igv_last = $detail->igv;

                $quantity = $items[$i]->quantity;
                $price = $items[$i]->price;

                $total = $quantity*$price;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;

                $detail->quantity = round($quantity, 2);
                $detail->price = round($price, 2);
                $detail->igv = round($igv);
                $detail->save();

                $material_order->quantity_request =  round($quantity, 2);
                $material_order->save();

                $orderExpress->igv = round(($orderExpress->igv - $igv_last),2);
                $orderExpress->total = round(($orderExpress->total - $total_last),2);
                $orderExpress->save();

                $orderExpress->igv = round(($orderExpress->igv + $igv),2);
                $orderExpress->total = round(($orderExpress->total + $total),2);

                $orderExpress->save();
            }

        }

        return response()->json(['message' => 'Detalle express modificado con éxito.'], 200);

    }

    public function destroyDetail($idDetail, $idMaterial)
    {
        $detail = OrderPurchaseDetail::find($idDetail);
        $orderExpress = OrderPurchase::find($detail->order_purchase_id);
        $orderExpress->igv = $orderExpress->igv - $detail->igv;
        $orderExpress->total = $orderExpress->total - $detail->total;
        $orderExpress->save();

        $material_order = MaterialOrder::where('material_id', $idMaterial)
            ->where('order_purchase_detail_id', $detail->id)->first();

        if ( $material_order->quantity_entered > 0 ) {
            return response()->json(['message' => 'No se puede eliminar el detalle porque ya hay un ingreso.'], 422);
        } else {
            $material_order->delete();
            $igv = $detail->igv;
            $total = $detail->total;
            $orderExpress->igv = $orderExpress->igv - $igv;
            $orderExpress->total = $orderExpress->total - $total;
            $orderExpress->save();
            $detail->delete();
        }

        return response()->json(['message' => 'Detalle express eliminado con éxito.'], 200);

    }

    public function destroyOrderPurchaseExpress($order_id)
    {
        $orderPurchase = OrderPurchase::find($order_id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $orderPurchase->id)->get();
        foreach ( $details as $detail )
        {
            $material_order = MaterialOrder::where('material_id', $detail->material_id)
                ->where('order_purchase_detail_id', $detail->id)->first();
            if ( $material_order->quantity_entered > 0 ) {
                return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
            } else {
                $material_order->delete();
            }
            $detail->delete();

        }
        $orderPurchase->delete();

        return response()->json(['message' => 'Detalle express eliminado con éxito.'], 200);

    }

    public function getAllOrderExpress()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->get();
        return datatables($orders)->toJson();
    }
}
