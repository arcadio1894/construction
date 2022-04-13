<?php

namespace App\Http\Controllers;

use App\FollowMaterial;
use App\Http\Requests\StoreOrderPurchaseRequest;
use App\MaterialOrder;
use App\MaterialTaken;
use App\Notification;
use App\NotificationUser;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use App\PaymentDeadline;
use App\Quote;
use App\Supplier;
use App\SupplierCredit;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class OrderPurchaseController extends Controller
{
    public function indexOrderPurchaseExpressAndNormal()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexGeneral', compact('permissions'));
    }

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

        // TODO: Maxcode con trashed
        $maxCode = OrderPurchase::withTrashed()->max('id');
        $maxId = $maxCode + 1;
        //$maxCode = OrderPurchase::max('code');
        //$maxId = (int)substr($maxCode,3) + 1;
        $length = 5;

        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        //dd($codeOrder);
        $materials = [];
        $materials_quantity = [];

        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->materials as $material )
                {
                    array_push($materials, $material->material_id);
                    //$urlQuote = '<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', $quote->id).'" data-toggle="tooltip" data-placement="top" title="'.(float)$material->quantity*(float)$equipment->quantity.'">'.$quote->code.'</a>';
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
            $cantidadEnCotizaciones = $item['quantity'];
            $stockReal = $item['material_complete']->stock_current;
            $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
            $tengoReal = $stockReal + $amount;
            $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
            $faltaReal = $cantidadEnCotizaciones - $materials_taken;
            $balance = $faltaReal - $tengoReal;
            if ( $balance > 0 )
            {
                array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
            }

            /*if ( $item['material_complete']->stock_current < $item['quantity'] )
            {
                //$stringQuote = '<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', 39).'" data-toggle="tooltip" data-placement="top" title="4">COT-00039</a> <a class="btn btn-primary btn-xs" href="'.route('quote.show', 27).'" data-toggle="tooltip" data-placement="top" title="9" target="_blank">COT-00027</a>';
                $material_missing = MaterialOrder::where('material_id', $item['material_id'])->first();
                $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
                $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
                $missing = (float)$item['quantity'] - (float)$item['material_complete']->stock_current;
                if ( !isset($material_missing) )
                {
                    $missing_real = $missing - $materials_taken;
                    //array_push($array_materials, array('quote'=>$stringQuote,'material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));
                    array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));

                } else {
                    if ( $missing > $amount )
                    {
                        $missing_real = $missing - $amount - $materials_taken;
                        //array_push($array_materials, array('quote'=>$stringQuote,'material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));
                        array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $missing_real));

                    }
                }

            }*/
        }

        //dump($materials_quantity);
        //dump($array_materials);

        $arrayMaterialsFinal = [];

        foreach ( $array_materials as $material )
        {
            $stringQuote = '';
            foreach ( $quotesRaised as $quote )
            {
                $quantity = 0;
                foreach ($quote->equipments as $equipment)
                {

                    foreach ($equipment->materials as $material2) {
                        //dump($material2->material_id == $material['material_id']);
                        if ( $material2->material_id == $material['material_id'] )
                        {
                            $quantity += $material2->quantity*$equipment->quantity;
                        }
                    }
                }
                if ( $quantity > 0 )
                {
                    $stringQuote = $stringQuote.'<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', $quote->id).'" data-toggle="tooltip" data-placement="top" title="'.$quantity.'">'.$quote->code.'</a> ';
                }
            }
            //dump($stringQuote);
            array_push($arrayMaterialsFinal, array('material_id'=>$material['material_id'], 'material'=>$material['material'], 'material_complete'=>$material['material_complete'], 'quantity'=> $material['quantity'], 'missing_amount'=> $material['missing_amount'], 'quotes'=>$stringQuote));
        }
        //dump($arrayMaterialsFinal);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderPurchase.createExpress', compact('users', 'codeOrder', 'suppliers', 'arrayMaterialsFinal', 'payment_deadlines'));
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
            $maxCode = OrderPurchase::withTrashed()->max('id');
            $maxId = $maxCode + 1;
            $length = 5;
            $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderPurchase = OrderPurchase::create([
                'code' => $codeOrder,
                'quote_supplier' => $request->get('quote_supplier'),
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
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
                'type' => 'e',
                'status_order' => 'stand_by'
            ]);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseDetail = OrderPurchaseDetail::create([
                    'order_purchase_id' => $orderPurchase->id,
                    'material_id' => $items[$i]->id_material,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                // TODO: Revisamos si hay un material en seguimiento y creamos
                // TODO: la notificacion y cambiamos el estado
                $follows = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                    ->get();
                if ( isset($follows) )
                {
                    // TODO: Creamos notificacion y cambiamos el estado
                    // Crear notificacion
                    $notification = Notification::create([
                        'content' => 'El material ' . $orderPurchaseDetail->material->full_description . ' ha sido pedido.',
                        'reason_for_creation' => 'follow_material',
                        'user_id' => Auth::user()->id,
                        'url_go' => route('follow.index')
                    ]);

                    // Roles adecuados para recibir esta notificación admin, logistica
                    $users = User::role(['admin', 'operator'])->get();
                    foreach ( $users as $user )
                    {
                        $followUsers = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                            ->where('user_id', $user->id)
                            ->get();
                        if ( !$followUsers->isEmpty() )
                        {
                            foreach ( $user->roles as $role )
                            {
                                NotificationUser::create([
                                    'notification_id' => $notification->id,
                                    'role_id' => $role->id,
                                    'user_id' => $user->id,
                                    'read' => false,
                                    'date_read' => null,
                                    'date_delete' => null
                                ]);
                            }
                        }
                    }
                    foreach ( $follows as $follow )
                    {
                        $follow->state = 'in_order';
                        $follow->save();
                    }
                }

                $total = $orderPurchaseDetail->total_detail;
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

            // Si el plazo indica credito, se crea el credito
            if ( isset($orderPurchase->deadline) )
            {
                if ( $orderPurchase->deadline->credit == 1 || $orderPurchase->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderPurchase->deadline->id);
                    //$fecha_issue = Carbon::parse($orderPurchase->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderPurchase->supplier->id,
                        'total_soles' => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:null,
                        'total_dollars' => ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:null,
                        //'date_issue' => $orderPurchase->date_order,
                        'order_purchase_id' => $orderPurchase->id,
                        'state_credit' => 'outstanding',
                        'order_service_id' => null,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderPurchase->code,
                        'payment_deadline_id' => $orderPurchase->payment_deadline_id
                    ]);
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Su orden express con el código '.$codeOrder.' se guardó con éxito.'], 200);

    }

    public function showOrderPurchaseExpress($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user', 'deadline'])->find($id);
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
            $orderPurchase->payment_deadline_id = ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null;
            $orderPurchase->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderPurchase->date_arrival = ($request->has('date_arrival')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_arrival')) : Carbon::now();
            $orderPurchase->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderPurchase->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderPurchase->payment_condition = ($request->has('purchase_condition')) ? $request->get('purchase_condition') : '';
            $orderPurchase->currency_order = ($request->get('state') === 'true') ? 'PEN': 'USD';
            $orderPurchase->observation = $request->get('observation');
            $orderPurchase->quote_supplier = $request->get('quote_supplier');
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
                        'total_detail' => (float) $items[$i]->total,
                    ]);

                    $total = $orderPurchaseDetail->total_detail;
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

            // Si la orden de compra express se modifica, el credito tambien se modificara
            $credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $deadline = PaymentDeadline::find($credit->deadline->id);
                //$fecha_issue = Carbon::parse($orderPurchase->date_order);
                //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                // TODO: Poner dias
                //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                $credit->supplier_id = $orderPurchase->supplier->id;
                $credit->total_soles = ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:null;
                $credit->total_dollars = ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:null;
                //$credit->date_issue = $orderPurchase->date_order;
                //$credit->date_expiration = $fecha_expiration;
                //$credit->days_to_expiration = $dias_to_expire;
                $credit->code_order = $orderPurchase->code;
                $credit->payment_deadline_id = $orderPurchase->payment_deadline_id;
                $credit->save();
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
            $cantidadEnCotizaciones = $item['quantity'];
            $stockReal = $item['material_complete']->stock_current;
            $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
            $tengoReal = $stockReal + $amount;
            $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
            $faltaReal = $cantidadEnCotizaciones - $materials_taken;
            $balance = $faltaReal - $tengoReal;
            if ( $balance > 0 )
            {
                array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
            }
            /*if ( $item['material_complete']->stock_current < $item['quantity'] )
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

            }*/
        }

        $arrayMaterialsFinal = [];

        foreach ( $array_materials as $material )
        {
            $stringQuote = '';
            foreach ( $quotesRaised as $quote )
            {
                $quantity = 0;
                foreach ($quote->equipments as $equipment)
                {

                    foreach ($equipment->materials as $material2) {
                        //dump($material2->material_id == $material['material_id']);
                        if ( $material2->material_id == $material['material_id'] )
                        {
                            $quantity += $material2->quantity*$equipment->quantity;
                        }
                    }
                }
                if ( $quantity > 0 )
                {
                    $stringQuote = $stringQuote.'<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', $quote->id).'" data-toggle="tooltip" data-placement="top" title="'.$quantity.'">'.$quote->code.'</a> ';
                }
            }
            //dump($stringQuote);
            array_push($arrayMaterialsFinal, array('material_id'=>$material['material_id'], 'material'=>$material['material'], 'material_complete'=>$material['material_complete'], 'quantity'=> $material['quantity'], 'missing_amount'=> $material['missing_amount'], 'quotes'=>$stringQuote));
        }

        $order = OrderPurchase::with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderPurchase.editExpress', compact('order', 'details', 'suppliers', 'users', 'arrayMaterialsFinal', 'payment_deadlines'));
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
                if ( isset($material_order) )
                {
                    if ( $material_order->quantity_entered > 0 ) {
                        return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
                    } else {
                        //$total_last = $detail->price*$detail->quantity;
                        //$igv_last = $detail->igv;

                        $quantity = (float) $items[$i]->quantity;
                        $price = (float) $items[$i]->price;
                        $total_final = (float) $items[$i]->total;

                        $total = round($total_final, 2);
                        $subtotal = round($total_final / 1.18, 2);
                        $igv = $total - $subtotal;

                        $detail->quantity = round($quantity, 2);
                        $detail->price = round($price, 2);
                        $detail->igv = round($igv,2);
                        $detail->total_detail = round($total,2);
                        $detail->save();

                        $material_order->quantity_request =  round($quantity, 2);
                        $material_order->save();

                        //$orderExpress->igv = round(($orderExpress->igv - $igv_last),2);
                        //$orderExpress->total = round(($orderExpress->total - $total_last),2);
                        //$orderExpress->save();

                        //$orderExpress->igv = round(($orderExpress->igv + $igv),2);
                        //$orderExpress->total = round(($orderExpress->total + $total),2);

                        //$orderExpress->save();

                        // Si la orden de compra express se modifica, el credito tambien se modificara
                        /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                            ->where('state_credit', 'outstanding')->first();
                        if ( isset($credit) )
                        {
                            $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                            $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                            $credit->save();
                        }*/
                    }
                } else {
                    //$total_last = $detail->price*$detail->quantity;
                    //600
                    //$igv_last = $detail->igv;
                    //91.53

                    $quantity = (float) $items[$i]->quantity;
                    //4
                    $price = (float) $items[$i]->price;
                    //200
                    $total_final = (float) $items[$i]->total;

                    $total = round($total_final, 2);
                    //800
                    $subtotal = round($total_final / 1.18, 2);

                    //$total = round($quantity*$price, 2);
                    //800
                    //$subtotal = round($total / 1.18, 2);
                    //677.97
                    $igv = $total - $subtotal;
                    //122.03
                    $detail->quantity = round($quantity, 2);
                    $detail->price = round($price, 2);
                    $detail->igv = round($igv,2);
                    $detail->total_detail = round($total,2);
                    $detail->save();

                    //$orderExpress->igv = round(($orderExpress->igv - $igv_last),2);
                    //$orderExpress->total = round(($orderExpress->total - $total_last),2);
                    //$orderExpress->save();

                    //$orderExpress->igv = round(($orderExpress->igv + $igv),2);
                    //$orderExpress->total = round(($orderExpress->total + $total),2);

                    //$orderExpress->save();

                    // Si la orden de compra express se modifica, el credito tambien se modificara
                    /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                        ->where('state_credit', 'outstanding')->first();
                    if ( isset($credit) )
                    {
                        $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                        $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                        $credit->save();
                    }*/
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
            //$orderExpress->igv = $orderExpress->igv - $detail->igv;
            //$orderExpress->total = $orderExpress->total - ($detail->quantity*$detail->price);
            //$orderExpress->save();

            // Si la orden de compra express se modifica, el credito tambien se modificara
            /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                $credit->save();
            }*/

            $material_order = MaterialOrder::where('material_id', $idMaterial)
                ->where('order_purchase_detail_id', $detail->id)->first();

            if ( $material_order->quantity_entered > 0 ) {
                return response()->json(['message' => 'No se puede eliminar el detalle porque ya hay un ingreso.'], 422);
            } else {
                $material_order->delete();
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
            if ( isset($material_order) )
            {
                if ( $material_order->quantity_entered > 0 ) {
                    return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
                } else {
                    $material_order->delete();
                }
            }

            $detail->delete();

        }

        // Si la orden de servicio se elimina, y el credito es pendiente se debe eliminar
        $credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
            ->where('state_credit', 'outstanding')->first();
        if ( isset($credit) )
        {
            $credit->delete();
        }

        $orderPurchase->delete();

        return response()->json(['message' => 'Orden express eliminada con éxito.'], 200);

    }

    public function getAllOrderExpress()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('type', 'e')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function getAllOrderGeneral()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function createOrderPurchaseNormal()
    {
        $suppliers = Supplier::all();
        $users = User::all();

        // TODO: WITH TRASHED
        $maxCode = OrderPurchase::withTrashed()->max('id');
        $maxId = $maxCode + 1;
        //$maxCode = OrderPurchase::max('code');
        //$maxId = (int)substr($maxCode,3) + 1;
        $length = 5;
        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderPurchase.createNormal', compact('users', 'codeOrder', 'suppliers', 'payment_deadlines'));

    }

    public function storeOrderPurchaseNormal(StoreOrderPurchaseRequest $request)
    {
        //dd($request);
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
            $maxCode = OrderPurchase::withTrashed()->max('id');
            $maxId = $maxCode + 1;
            $length = 5;
            $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderPurchase = OrderPurchase::create([
                'code' => $codeOrder,
                'quote_supplier' => $request->get('quote_supplier'),
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
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
                'type' => 'n',
                'regularize' => ($request->has('regularize_order')) ? 'r':'nr',
                'status_order' => 'stand_by'
            ]);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseDetail = OrderPurchaseDetail::create([
                    'order_purchase_id' => $orderPurchase->id,
                    'material_id' => $items[$i]->id_material,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                // TODO: Revisamos si hay un material en seguimiento y creamos
                // TODO: la notificacion y cambiamos el estado
                $follows = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                    ->get();
                if ( isset($follows) )
                {
                    // TODO: Creamos notificacion y cambiamos el estado
                    // Crear notificacion
                    $notification = Notification::create([
                        'content' => 'El material ' . $orderPurchaseDetail->material->full_description . ' ha sido pedido.',
                        'reason_for_creation' => 'follow_material',
                        'user_id' => Auth::user()->id,
                        'url_go' => route('follow.index')
                    ]);

                    // Roles adecuados para recibir esta notificación admin, logistica
                    $users = User::role(['admin', 'operator'])->get();
                    foreach ( $users as $user )
                    {
                        $followUsers = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                            ->where('user_id', $user->id)
                            ->get();
                        if ( !$followUsers->isEmpty() )
                        {
                            foreach ( $user->roles as $role )
                            {
                                NotificationUser::create([
                                    'notification_id' => $notification->id,
                                    'role_id' => $role->id,
                                    'user_id' => $user->id,
                                    'read' => false,
                                    'date_read' => null,
                                    'date_delete' => null
                                ]);
                            }
                        }
                    }
                    foreach ( $follows as $follow )
                    {
                        $follow->state = 'in_order';
                        $follow->save();
                    }
                }

                $total = $orderPurchaseDetail->total_detail;
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

            // Si el plazo indica credito, se crea el credito
            if ( isset($orderPurchase->deadline) )
            {
                if ( $orderPurchase->deadline->credit == 1 || $orderPurchase->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderPurchase->deadline->id);
                    //$fecha_issue = Carbon::parse($orderPurchase->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderPurchase->supplier->id,
                        'total_soles' => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:null,
                        'total_dollars' => ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:null,
                        //'date_issue' => $orderPurchase->date_order,
                        'order_purchase_id' => $orderPurchase->id,
                        'state_credit' => 'outstanding',
                        'order_service_id' => null,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderPurchase->code,
                        'payment_deadline_id' => $orderPurchase->payment_deadline_id
                    ]);
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Su orden normal con el código '.$codeOrder.' se guardó con éxito.'], 200);

    }

    public function getAllOrderNormal()
    {
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('type', 'n')
            ->orderBy('created_at', 'desc')
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

        $order = OrderPurchase::with(['supplier', 'approved_user', 'deadline'])->find($id);
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
            if ( isset($material_order) )
            {
                if ( $material_order->quantity_entered > 0 ) {
                    return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
                } else {
                    $material_order->delete();
                }
            }

            $detail->delete();

        }

        // Si la orden de servicio se elimina, y el credito es pendiente se debe eliminar
        $credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
            ->where('state_credit', 'outstanding')->first();
        if ( isset($credit) )
        {
            $credit->delete();
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

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderPurchase.editNormal', compact('order', 'details', 'suppliers', 'users', 'payment_deadlines'));
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
                if ( isset($material_order) )
                {
                    if ( $material_order->quantity_entered > 0 ) {
                        return response()->json(['message' => 'No se puede modificar el detalle porque ya hay un ingreso.'], 422);
                    } else {
                        //$total_last = $detail->price*$detail->quantity;
                        //600
                        //$igv_last = $detail->igv;
                        //91.53

                        $quantity = (float) $items[$i]->quantity;
                        //4
                        $price = (float) $items[$i]->price;
                        //200
                        $total_final = (float) $items[$i]->total;

                        $total = round($total_final, 2);
                        //800
                        $subtotal = round($total_final / 1.18, 2);
                        //677.97
                        $igv = $total - $subtotal;
                        //122.03
                        $detail->quantity = round($quantity, 2);
                        $detail->price = round($price, 2);
                        $detail->igv = round($igv,2);
                        $detail->total_detail = round($total,2);
                        $detail->save();

                        $material_order->quantity_request =  round($quantity, 2);
                        $material_order->save();

                        //$orderExpress->igv = round(($orderExpress->igv - $igv_last),2);
                        //$orderExpress->total = round(($orderExpress->total - $total_last),2);
                        //$orderExpress->save();

                        //$orderExpress->igv = round(($orderExpress->igv + $igv),2);
                        //$orderExpress->total = round(($orderExpress->total + $total),2);

                        //$orderExpress->save();

                        // Si la orden de compra express se modifica, el credito tambien se modificara
                        /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                            ->where('state_credit', 'outstanding')->first();
                        if ( isset($credit) )
                        {
                            $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                            $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                            $credit->save();
                        }*/
                    }
                } else {
                    //$total_last = $detail->price*$detail->quantity;
                    //600
                    //$igv_last = $detail->igv;
                    //91.53

                    $quantity = (float) $items[$i]->quantity;
                    //4
                    $price = (float) $items[$i]->price;
                    //200
                    $total_final = (float) $items[$i]->total;

                    $total = round($total_final, 2);
                    //800
                    $subtotal = round($total_final / 1.18, 2);

                    //$total = round($quantity*$price, 2);
                    //800
                    //$subtotal = round($total / 1.18, 2);
                    //677.97
                    $igv = $total - $subtotal;
                    //122.03
                    $detail->quantity = round($quantity, 2);
                    $detail->price = round($price, 2);
                    $detail->igv = round($igv,2);
                    $detail->total_detail = round($total,2);
                    $detail->save();

                    //$orderExpress->igv = round(($orderExpress->igv - $igv_last),2);
                    //$orderExpress->total = round(($orderExpress->total - $total_last),2);
                    //$orderExpress->save();

                    //$orderExpress->igv = round(($orderExpress->igv + $igv),2);
                    //$orderExpress->total = round(($orderExpress->total + $total),2);

                    //$orderExpress->save();

                    // Si la orden de compra express se modifica, el credito tambien se modificara
                    /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                        ->where('state_credit', 'outstanding')->first();
                    if ( isset($credit) )
                    {
                        $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                        $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                        $credit->save();
                    }*/
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
            //$orderExpress->igv = $orderExpress->igv - $detail->igv;
            //$orderExpress->total = $orderExpress->total - ($detail->quantity*$detail->price);
            //$orderExpress->save();

            // Si la orden de compra express se modifica, el credito tambien se modificara
            /*$credit = SupplierCredit::where('order_purchase_id', $orderExpress->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $credit->total_soles = ($orderExpress->currency_order == 'PEN') ? $orderExpress->total:null;
                $credit->total_dollars = ($orderExpress->currency_order == 'USD') ? $orderExpress->total:null;
                $credit->save();
            }*/

            $material_order = MaterialOrder::where('material_id', $idMaterial)
                ->where('order_purchase_detail_id', $detail->id)->first();
            if ( isset($material_order) )
            {
                if ( $material_order->quantity_entered > 0 ) {
                    return response()->json(['message' => 'No se puede eliminar el detalle porque ya hay un ingreso.'], 422);
                } else {
                    $material_order->delete();
                    $detail->delete();
                }
            } else {
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
            $orderPurchase->payment_deadline_id = ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null;
            $orderPurchase->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderPurchase->date_arrival = ($request->has('date_arrival')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_arrival')) : Carbon::now();
            $orderPurchase->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderPurchase->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderPurchase->payment_condition = ($request->has('purchase_condition')) ? $request->get('purchase_condition') : '';
            $orderPurchase->currency_order = ($request->get('state') === 'true') ? 'PEN': 'USD';
            $orderPurchase->regularize = ($request->get('regularize') === 'true') ? 'r': 'nr';
            $orderPurchase->observation = $request->get('observation');
            $orderPurchase->quote_supplier = $request->get('quote_supplier');
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
                        'total_detail' => (float) $items[$i]->total,
                    ]);

                    $total = $orderPurchaseDetail->total_detail;
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

            // Si la orden de compra express se modifica, el credito tambien se modificara
            $credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $deadline = PaymentDeadline::find($orderPurchase->deadline->id);
                //$fecha_issue = Carbon::parse($orderPurchase->date_order);
                //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                // TODO: Poner dias
                //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                $credit->supplier_id = $orderPurchase->supplier->id;
                $credit->total_soles = ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:null;
                $credit->total_dollars = ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:null;
                //$credit->date_issue = $orderPurchase->date_order;
                //$credit->date_expiration = $fecha_expiration;
                //$credit->days_to_expiration = $dias_to_expire;
                $credit->code_order = $orderPurchase->code;
                $credit->payment_deadline_id = $orderPurchase->payment_deadline_id;
                $credit->save();
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden compra normal modificada con éxito.'], 200);

    }

    public function printOrderPurchase($id)
    {
        $purchase_order = null;
        $purchase_order = OrderPurchase::with('approved_user')
            ->with('deadline')
            ->with(['details' => function ($query) {
                $query->with(['material']);
            }])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.entryPurchase', compact('purchase_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_ ' . $purchase_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function changeStatusOrderPurchase($order_id, $status)
    {
        DB::beginTransaction();
        try {

            $orderPurchase = OrderPurchase::find($order_id);
            $orderPurchase->status_order = $status;
            $orderPurchase->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Estado modificado.'], 200);

    }

}
