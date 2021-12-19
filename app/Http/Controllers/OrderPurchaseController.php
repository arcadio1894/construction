<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderPurchaseRequest;
use App\MaterialOrder;
use App\MaterialTaken;
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
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')
            ->with('equipments')->get();

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
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

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
                $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
                $missing = (float)$item['quantity'] - (float)$item['material_complete']->stock_current;
                if ( !isset($material_missing) )
                {
                    $missing_real = $missing - $materials_taken;
                    array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));
                } else {
                    if ( $missing > $amount )
                    {
                        $missing_real = $missing - $amount - $materials_taken;
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

        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        //dump($request->get('date_invoice'));
        $fecha = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
        //$fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_order'));

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
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'type' => 'e'
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
            $orderPurchase->igv = (float) $request->get('taxes_send');
            $orderPurchase->total = (float) $request->get('total_send');
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

                    $total = round($orderPurchaseDetail->quantity*$orderPurchaseDetail->price, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = round($total - $subtotal, 2);
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
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')
            ->with('equipments')->get();

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
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

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
        DB::beginTransaction();
        try {
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
                    $total_last = $detail->price*$detail->quantity;
                    $igv_last = $detail->igv;

                    $quantity = (float) $items[$i]->quantity;
                    $price = (float) $items[$i]->price;

                    $total = round($quantity*$price, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = $total - $subtotal;

                    $detail->quantity = round($quantity, 2);
                    $detail->price = round($price, 2);
                    $detail->igv = round($igv,2);
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
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle express modificado con éxito.'], 200);

    }

    public function destroyDetail($idDetail, $idMaterial)
    {
        DB::beginTransaction();
        try {
            $detail = OrderPurchaseDetail::find($idDetail);
            $orderExpress = OrderPurchase::find($detail->order_purchase_id);
            $orderExpress->igv = $orderExpress->igv - $detail->igv;
            $orderExpress->total = $orderExpress->total - ($detail->quantity*$detail->price);
            $orderExpress->save();

            $material_order = MaterialOrder::where('material_id', $idMaterial)
                ->where('order_purchase_detail_id', $detail->id)->first();

            if ( $material_order->quantity_entered > 0 ) {
                return response()->json(['message' => 'No se puede eliminar el detalle porque ya hay un ingreso.'], 422);
            } else {
                $material_order->delete();
                $igv = $detail->igv;
                $total = $detail->quantity*$detail->price;
                $orderExpress->igv = $orderExpress->igv - $igv;
                $orderExpress->total = $orderExpress->total - $total;
                $orderExpress->save();
                $detail->delete();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
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

        return response()->json(['message' => 'Orden express eliminada con éxito.'], 200);

    }

    public function getAllOrderExpress()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('type', 'e')
            ->get();
        return datatables($orders)->toJson();
    }

    public function createOrderPurchaseNormal()
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $maxId = OrderPurchase::max('id')+1;
        $length = 5;
        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        return view('orderPurchase.createNormal', compact('users', 'codeOrder', 'suppliers'));

    }

    public function storeOrderPurchaseNormal(StoreOrderPurchaseRequest $request)
    {
        $validated = $request->validated();

        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        //dump($request->get('date_invoice'));
        $fecha = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
        //$fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_order'));

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
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'type' => 'n'
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
        return response()->json(['message' => 'Orden compra normal guardada con éxito.'], 200);

    }

    public function getAllOrderNormal()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('type', 'n')
            ->get();
        return datatables($orders)->toJson();
    }

    public function indexOrderPurchaseNormal()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexNormal', compact('permissions'));
    }

    public function showOrderPurchaseNormal($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.showNormal', compact('order', 'details', 'suppliers', 'users'));

    }

    public function destroyOrderPurchaseNormal($order_id)
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

        return response()->json(['message' => 'Orden normal eliminada con éxito.'], 200);

    }

    public function editOrderPurchaseNormal($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.editNormal', compact('order', 'details', 'suppliers', 'users'));
    }

    public function updateNormalDetail(Request $request, $detail_id)
    {
        DB::beginTransaction();
        try {
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
                    $total_last = $detail->price*$detail->quantity;
                    //600
                    $igv_last = $detail->igv;
                    //91.53

                    $quantity = (float) $items[$i]->quantity;
                    //4
                    $price = (float) $items[$i]->price;
                    //200

                    $total = round($quantity*$price, 2);
                    //800
                    $subtotal = round($total / 1.18, 2);
                    //677.97
                    $igv = $total - $subtotal;
                    //122.03
                    $detail->quantity = round($quantity, 2);
                    $detail->price = round($price, 2);
                    $detail->igv = round($igv,2);
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
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle normal modificado con éxito.'], 200);

    }

    public function destroyNormalDetail($idDetail, $idMaterial)
    {
        DB::beginTransaction();
        try {
            $detail = OrderPurchaseDetail::find($idDetail);
            $orderExpress = OrderPurchase::find($detail->order_purchase_id);
            $orderExpress->igv = $orderExpress->igv - $detail->igv;
            $orderExpress->total = $orderExpress->total - ($detail->quantity*$detail->price);
            $orderExpress->save();

            $material_order = MaterialOrder::where('material_id', $idMaterial)
                ->where('order_purchase_detail_id', $detail->id)->first();

            if ( $material_order->quantity_entered > 0 ) {
                return response()->json(['message' => 'No se puede eliminar el detalle porque ya hay un ingreso.'], 422);
            } else {
                $material_order->delete();
                $igv = $detail->igv;
                $total = $detail->quantity*$detail->price;
                $orderExpress->igv = $orderExpress->igv - $igv;
                $orderExpress->total = $orderExpress->total - $total;
                $orderExpress->save();
                $detail->delete();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle normal eliminado con éxito.'], 200);

    }

    public function updateOrderPurchaseNormal(StoreOrderPurchaseRequest $request)
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
            $orderPurchase->igv = (float) $request->get('taxes_send');
            $orderPurchase->total = (float) $request->get('total_send');
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

                    $total = round($orderPurchaseDetail->quantity*$orderPurchaseDetail->price, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = round($total - $subtotal, 2);
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
        return response()->json(['message' => 'Orden compra normal modificada con éxito.'], 200);

    }

}
