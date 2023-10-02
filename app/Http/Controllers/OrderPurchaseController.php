<?php

namespace App\Http\Controllers;

use App\Audit;
use App\Exports\ReportOrderPurchaseExport;
use App\FollowMaterial;
use App\Http\Requests\StoreOrderPurchaseRequest;
use App\Item;
use App\Material;
use App\MaterialOrder;
use App\MaterialTaken;
use App\Notification;
use App\NotificationUser;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use App\Output;
use App\OutputDetail;
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

    public function indexOrderPurchaseDelete()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.deleteGeneral', compact('permissions'));
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
        $begin = microtime(true);
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
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            array_push($materials, $material->material_id);
                            //$urlQuote = '<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', $quote->id).'" data-toggle="tooltip" data-placement="top" title="'.(float)$material->quantity*(float)$equipment->quantity.'">'.$quote->code.'</a>';
                            array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));
                        }

                    }
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

        // TODO: Nueva logica para hallar las cantidades
        $array_takens = [];
        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            $materials_taken = MaterialTaken::where('equipment_id', $equipment->id)
                                ->where('material_id', $material->material_id)
                                ->where('type_output', 'orn')
                                ->get();

                            foreach ( $materials_taken as $item )
                            {
                                array_push($array_takens, array('material_id'=>$item->material_id, 'quantity'=> (float)$item->quantity_request));
                            }
                        }

                    }
                }

            }
        }

        //$array_materials = [];
        $new_arr2 = array();
        foreach($array_takens as $item) {
            if(isset($new_arr2[$item['material_id']])) {
                $new_arr2[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr2[$item['material_id']] = $item;
        }

        $materials_takens = array_values($new_arr2);

        foreach ( $materials_quantity as $item )
        {
            //dump('Logica  ' . $item['material_id'] );
            $cantidadEnCotizaciones = $item['quantity'];
            //dump('CC  ' . $cantidadEnCotizaciones);
            $stockReal = $item['material_complete']->stock_current;
            //dump('stock  ' . $stockReal);
            $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
            //dump('orden  ' . $amount);
            $tengoReal = $stockReal + $amount;
            //dump('TR  ' . $tengoReal);
            //dump('taken  ' . $materials_taken);
            $material_taken = (array_search($item['material_id'], array_column($materials_takens, 'material_id')) == null) ? 0: $materials_takens[array_search($item['material_id'], array_column($materials_takens, 'material_id'))]['quantity'];
            $faltaReal = $cantidadEnCotizaciones - $material_taken;

            //dump('FR  ' . $faltaReal);
            $balance = $faltaReal - $tengoReal;
            //dump('bal  ' . $balance);

            if ( $balance > 0 )
            {
                array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
            }
        }

        //foreach ( $materials_quantity as $item )
        //{
        //    $cantidadEnCotizaciones = $item['quantity'];
        //    $stockReal = $item['material_complete']->stock_current;
        //    $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
        //    $tengoReal = $stockReal + $amount;
        //    $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
        //    $faltaReal = $cantidadEnCotizaciones - $materials_taken;
        //    $balance = $faltaReal - $tengoReal;
        //    if ( $balance > 0 )
        //    {
        //        array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
        //    }

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
        //}

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
                    if ( !$equipment->finished ) {
                        foreach ($equipment->materials as $material2) {
                            //dump($material2->material_id == $material['material_id']);
                            if ($material2->material_id == $material['material_id'] && $material2->replacement == 0) {
                                $quantity += $material2->quantity * $equipment->quantity;
                            }
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

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear Orden de Compra Express VISTA',
            'time' => $end
        ]);

        return view('orderPurchase.createExpress', compact('users', 'codeOrder', 'suppliers', 'arrayMaterialsFinal', 'payment_deadlines', 'quotesRaised'));
    }

    public function getInformationQuantityMaterial($material_id)
    {
        $materialComplete = Material::find($material_id);
        //dd($material);
        //dump('Logica  ' . $item['material_id'] );
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')
            ->with('equipments')->get();

        $quoteQuantity = 0;
        $takenQuantity = 0;

        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            if ( $material->material_id == $material_id  ){
                                $materials_taken = MaterialTaken::where('equipment_id', $equipment->id)
                                    ->where('material_id', $material->material_id)
                                    ->where('type_output', 'orn')
                                    ->get();

                                foreach ( $materials_taken as $item )
                                {
                                    $takenQuantity+=(float)$item->quantity_request;
                                }

                                $quoteQuantity+=((float)$material->quantity*(float)$equipment->quantity);

                            }
                        }

                    }
                }

            }
        }

        $cantidadEnCotizaciones = $quoteQuantity;
        //dump('CC  ' . $cantidadEnCotizaciones);
        $stockReal = (float)$materialComplete->stock_current;
        //dump('stock  ' . $stockReal);
        $amount = MaterialOrder::where('material_id', $material_id)->sum('quantity_request') - MaterialOrder::where('material_id', $material_id)->sum('quantity_entered');
        //dump('orden  ' . $amount);
        $tengoReal = $stockReal + $amount;
        //dump('TR  ' . $tengoReal);
        //dump('taken  ' . $materials_taken);
        $material_taken = $takenQuantity;
        $faltaReal = $cantidadEnCotizaciones - $material_taken;

        //dump('FR  ' . $faltaReal);
        $balance = $faltaReal - $tengoReal;
        //dump('bal  ' . $balance);

        return response()->json([
            "cantidadCotizaciones" => $cantidadEnCotizaciones,
            "stockActual" => $stockReal,
            "cantidadOrdenes" => $amount,
            "cantidadDisponibleReal" => $tengoReal,
            "cantidadSolicitada" => $material_taken,
            "cantidadNecesitadaReal" => $faltaReal,
            "cantidadParaComprar" => $balance
        ]);

    }

    public function storeOrderPurchaseExpress(StoreOrderPurchaseRequest $request)
    {
        $begin = microtime(true);
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
            //$codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderPurchase = OrderPurchase::create([
                'code' => '',
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
                'status_order' => 'stand_by',
                'quote_id' => ($request->has('quote_id')) ? $request->get('quote_id') : null,

            ]);

            $codeOrder = '';
            if ( $maxId < $orderPurchase->id ){
                $codeOrder = 'OC-'.str_pad($orderPurchase->id,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            } else {
                $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            }

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
                if ( !$follows->isEmpty() )
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
            /*if ( isset($orderPurchase->deadline) )
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
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Crear Orden de Compra Express POST',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Su orden express con el código '.$codeOrder.' se guardó con éxito.'], 200);

    }

    public function showOrderPurchaseExpress($id)
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver Orden Compra Express',
            'time' => $end
        ]);
        return view('orderPurchase.showExpress', compact('order', 'details', 'suppliers', 'users'));

    }

    public function showOrderOperator($code)
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user', 'deadline'])->where('code', $code)->first();
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver Orden Compra Operador',
            'time' => $end
        ]);
        return view('orderPurchase.showOperator', compact('order', 'details', 'suppliers', 'users'));

    }

    public function updateOrderPurchaseExpress(StoreOrderPurchaseRequest $request)
    {
        $begin = microtime(true);
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
            $orderPurchase->quote_id = ($request->has('quote_id')) ? $request->get('quote_id') : null;
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
            /*$credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
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
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Editar Orden Compra Express',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden compra express modificada con éxito.'], 200);

    }

    public function editOrderPurchaseExpress($id)
    {
        $begin = microtime(true);
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
                if ( !$equipment->finished )
                {
                    foreach ($equipment->materials as $material) {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            array_push($materials, $material->material_id);
                            array_push($materials_quantity, array('material_id' => $material->material_id, 'material' => $material->material->full_description, 'material_complete' => $material->material, 'quantity' => (float)$material->quantity * (float)$equipment->quantity));

                        }

                    }
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

        // TODO: Nueva logica para hallar las cantidades
        $array_takens = [];
        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            $materials_taken = MaterialTaken::where('equipment_id', $equipment->id)
                                ->where('material_id', $material->material_id)
                                ->where('type_output', 'orn')
                                ->get();

                            foreach ( $materials_taken as $item )
                            {
                                array_push($array_takens, array('material_id'=>$item->material_id, 'quantity'=> (float)$item->quantity_request));
                            }
                        }

                    }
                }

            }
        }

        //$array_materials = [];
        $new_arr2 = array();
        foreach($array_takens as $item) {
            if(isset($new_arr2[$item['material_id']])) {
                $new_arr2[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr2[$item['material_id']] = $item;
        }

        $materials_takens = array_values($new_arr2);

        foreach ( $materials_quantity as $item )
        {
            //dump('Logica  ' . $item['material_id'] );
            $cantidadEnCotizaciones = $item['quantity'];
            //dump('CC  ' . $cantidadEnCotizaciones);
            $stockReal = $item['material_complete']->stock_current;
            //dump('stock  ' . $stockReal);
            $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
            //dump('orden  ' . $amount);
            $tengoReal = $stockReal + $amount;
            //dump('TR  ' . $tengoReal);

            $material_taken = (array_search($item['material_id'], array_column($materials_takens, 'material_id')) == null) ? 0: $materials_takens[array_search($item['material_id'], array_column($materials_takens, 'material_id'))]['quantity'];

            //dump('taken  ' . $materials_taken);
            $faltaReal = $cantidadEnCotizaciones - $material_taken;
            //dump('FR  ' . $faltaReal);
            $balance = $faltaReal - $tengoReal;
            //dump('bal  ' . $balance);

            if ( $balance > 0 )
            {
                array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
            }
        }

        //foreach ( $materials_quantity as $item )
        //{
        //    $cantidadEnCotizaciones = $item['quantity'];
        //    $stockReal = $item['material_complete']->stock_current;
        //    $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
        //    $tengoReal = $stockReal + $amount;
        //    $materials_taken = MaterialTaken::where('material_id', $item['material_id'])->sum('quantity_request');
        //    $faltaReal = $cantidadEnCotizaciones - $materials_taken;
        //    $balance = $faltaReal - $tengoReal;
        //    if ( $balance > 0 )
        //    {
        //        array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
        //    }
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
        //}

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
                        if ( $material2->material_id == $material['material_id'] && $material2->replacement == 0 )
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

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Editar Orden Compra Express VISTA',
            'time' => $end
        ]);

        return view('orderPurchase.editExpress', compact('order', 'details', 'suppliers', 'users', 'arrayMaterialsFinal', 'payment_deadlines', 'quotesRaised'));
    }

    public function updateDetail(Request $request, $detail_id)
    {
        $begin = microtime(true);
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
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Editar Orden Compra Detalle',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle express modificado con éxito.'], 200);

    }

    public function destroyDetail($idDetail, $idMaterial)
    {
        $begin = microtime(true);
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
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar Orden Compra Detalle',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle express eliminado con éxito.'], 200);

    }

    public function destroyOrderPurchaseExpress($order_id)
    {
        $begin = microtime(true);
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
        // TODO: Evitar eliminar el credito porque en el restore no se esta restaurando el credito
        /*$credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
            ->where('state_credit', 'outstanding')->first();
        if ( isset($credit) )
        {
            $credit->delete();
        }*/

        $orderPurchase->delete();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Eliminar Orden Compra',
            'time' => $end
        ]);

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
        $begin = microtime(true);
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->orderBy('created_at', 'desc')
            ->get();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Ordenes Compra General',
            'time' => $end
        ]);
        return datatables($orders)->toJson();
    }

    public function getOrderDeleteGeneral()
    {
        $begin = microtime(true);
        $orders = OrderPurchase::onlyTrashed()
            ->with(['supplier', 'approved_user'])
            ->orderBy('created_at', 'desc')
            ->get();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Ordenes eliminadas General',
            'time' => $end
        ]);
        return datatables($orders)->toJson();
    }

    public function createOrderPurchaseNormal()
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')->get();

        // TODO: WITH TRASHED
        $maxCode = OrderPurchase::withTrashed()->max('id');
        $maxId = $maxCode + 1;
        //$maxCode = OrderPurchase::max('code');
        //$maxId = (int)substr($maxCode,3) + 1;
        $length = 5;
        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear Orden compra Normal VISTA',
            'time' => $end
        ]);
        return view('orderPurchase.createNormal', compact('users', 'codeOrder', 'suppliers', 'payment_deadlines', 'quotesRaised'));

    }

    public function storeOrderPurchaseNormal(StoreOrderPurchaseRequest $request)
    {
        $begin = microtime(true);
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
            //$codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderPurchase = OrderPurchase::create([
                'code' => '',
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
                'status_order' => 'stand_by',
                'quote_id' => ($request->has('quote_id')) ? $request->get('quote_id') : null,

            ]);
            $codeOrder = '';
            if ( $maxId < $orderPurchase->id ){
                $codeOrder = 'OC-'.str_pad($orderPurchase->id,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            } else {
                $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            }

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
                if ( !$follows->isEmpty() )
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
            /*if ( isset($orderPurchase->deadline) )
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
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar Orden Compra Normal POST',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Su orden normal con el código '.$codeOrder.' se guardó con éxito.'], 200);

    }

    public function getAllOrderNormal()
    {
        $begin = microtime(true);
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('type', 'n')
            ->orderBy('created_at', 'desc')
            ->get();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener todas Orden Compra',
            'time' => $end
        ]);
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

    public function showOrderPurchaseDelete($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchase::withTrashed()
            ->with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderPurchaseDetail::withTrashed()
            ->where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.showDelete', compact('order', 'details', 'suppliers', 'users'));

    }

    public function destroyOrderPurchaseNormal($order_id)
    {
        $begin = microtime(true);
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
        /*$credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
            ->where('state_credit', 'outstanding')->first();
        if ( isset($credit) )
        {
            $credit->delete();
        }*/

        $orderPurchase->delete();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Eliminar Orden Compra Normal',
            'time' => $end
        ]);
        return response()->json(['message' => 'Orden normal eliminada con éxito.'], 200);

    }

    public function editOrderPurchaseNormal($id)
    {
        $suppliers = Supplier::all();
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')->get();
        $users = User::all();

        $order = OrderPurchase::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseDetail::where('order_purchase_id', $order->id)
            ->with(['material'])->get();

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderPurchase.editNormal', compact('order', 'details', 'suppliers', 'users', 'payment_deadlines', 'quotesRaised'));
    }

    public function updateNormalDetail(Request $request, $detail_id)
    {
        $begin = microtime(true);
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
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Editar Orden Compra Normal Detalle',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle normal modificado con éxito.'], 200);

    }

    public function destroyNormalDetail($idDetail, $idMaterial)
    {
        $begin = microtime(true);
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

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar Orden Compra Normal Detalle',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle normal eliminado con éxito.'], 200);

    }

    public function updateOrderPurchaseNormal(StoreOrderPurchaseRequest $request)
    {
        $begin = microtime(true);
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
            $orderPurchase->quote_id = ($request->has('quote_id')) ? $request->get('quote_id') : null;
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
            /*$credit = SupplierCredit::where('order_purchase_id', $orderPurchase->id)
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
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Modificar Orden Compra Normal',
                'time' => $end
            ]);
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

    public function printOrderPurchaseDelete($id)
    {
        $purchase_order = null;
        $purchase_order = OrderPurchase::withTrashed()
            ->with('approved_user')
            ->with('deadline')
            ->with(['details' => function ($query) {
                $query->withTrashed()->with(['material']);
            }])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.entryPurchase', compact('purchase_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_ ' . $purchase_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function restoreOrderPurchaseDelete($id)
    {
        $begin = microtime(true);
        $orderPurchase = OrderPurchase::onlyTrashed()->find($id);

        $details = OrderPurchaseDetail::onlyTrashed()
            ->where('order_purchase_id', $id)->get();
        foreach ( $details as $detail )
        {
            $detail->restore();

            MaterialOrder::create([
                'order_purchase_detail_id' => $detail->id,
                'material_id' => $detail->material_id,
                'quantity_request' => $detail->quantity,
                'quantity_entered' => 0
            ]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Restaurar orden de compra',
            'time' => $end
        ]);
        $orderPurchase->restore();

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

    public function indexOrderPurchaseRegularize()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexRegularize', compact('permissions'));
    }

    public function getAllOrderRegularize()
    {
        $begin = microtime(true);
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->where('regularize', 'r')
            ->orderBy('created_at', 'desc')
            ->get();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Orden Compra Regularizadas',
            'time' => $end
        ]);
        return datatables($orders)->toJson();
    }

    public function indexOrderPurchaseLost()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexLost', compact('permissions'));
    }

    public function getAllOrderPurchaseLost()
    {
        $begin = microtime(true);
        $orders = OrderPurchase::withTrashed()
            ->pluck('code')->toArray();
        //dump($orders);
        $ids = [];
        for ($i=0; $i< count($orders); $i++)
        {
            $id = (int) substr( $orders[$i], 3 );
            array_push($ids, $id);
        }
        //dump($ids);
        $lost = [];
        $iterator = 1;
        for ( $j=0; $j< count($ids); $j++ )
        {
            while( $iterator < $ids[$j] )
            {
                $codeOrder = 'OC-'.str_pad($iterator,5,"0", STR_PAD_LEFT);
                array_push($lost, ['code'=>$codeOrder]);
                $iterator++;
            }
            $iterator++;
        }
        //dd($lost);
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Orden Compra Perdidas',
            'time' => $end
        ]);
        return datatables($lost)->toJson();
    }

    public function reportOrderPurchase()
    {
        $begin = microtime(true);
        //dd($request);
        $start = $_GET['start'];
        $end = $_GET['end'];
        //dump($start);
        //dump($end);
        //dd();
        $orders_array = [];
        $dates = '';

        if ( $start == '' || $end == '' )
        {
            //dump('Descargar todos');
            $dates = 'TOTALES';
            $orders = OrderPurchase::with(['supplier', 'approved_user', 'details'])
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ( $orders as $order )
            {
                foreach ( $order->details as $detail) {
                    $total_con_igv = round((float)$detail->price * (float)$detail->quantity,2);
                    $date_order = ($order->date_order == null) ? '': Carbon::createFromFormat('Y-m-d', $order->date_order)->format('d-m-Y');
                    $date_arrival = ($order->date_arrival == null) ? '': Carbon::createFromFormat('Y-m-d', $order->date_arrival)->format('d-m-Y');
                    array_push($orders_array, [
                        'order' => $order->code,
                        'date_order' => ($order->date_order != null) ? $date_order:'No tiene',
                        'date_arrive' => ($order->date_arrival != null) ? $date_arrival:'No tiene',
                        'supplier' => ($order->supplier_id != null) ? $order->supplier->business_name:'No tiene',
                        'material' => ($detail->material_id != null) ? $detail->material->full_description:'No tiene',
                        'category' => ($detail->material_id == null || $detail->material->category_id == null ) ? 'No tiene' : $detail->material->category->name,
                        'quantity' => $detail->quantity,
                        'currency' => $order->currency_order,
                        'price_igv' => round((float)$detail->price, 2),
                        'price_sin_igv' => round((float)$detail->price/1.18, 2),
                        'total_igv' => ($detail->total_detail != null) ? $detail->total_detail : $total_con_igv,
                    ]);
                }

            }


        } else {
            $date_start = Carbon::createFromFormat('d/m/Y', $start);
            $end_start = Carbon::createFromFormat('d/m/Y', $end);

            $dates = 'DEL '. $start .' AL '. $end;
            $orders = OrderPurchase::with(['supplier', 'approved_user', 'details'])
                ->whereDate('date_order', '>=',$date_start)
                ->whereDate('date_order', '<=',$end_start)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ( $orders as $order )
            {
                foreach ( $order->details as $detail) {
                    $total_con_igv = round((float)$detail->price * (float)$detail->quantity,2);
                    $date_order = ($order->date_order == null) ? '': Carbon::createFromFormat('Y-m-d', $order->date_order)->format('d-m-Y');
                    $date_arrival = ($order->date_arrival == null) ? '': Carbon::createFromFormat('Y-m-d', $order->date_arrival)->format('d-m-Y');
                    array_push($orders_array, [
                        'order' => $order->code,
                        'date_order' => ($order->date_order != null) ? $date_order:'No tiene',
                        'date_arrive' => ($order->date_arrival != null) ? $date_arrival:'No tiene',
                        'supplier' => ($order->supplier_id != null) ? $order->supplier->business_name:'No tiene',
                        'material' => ($detail->material_id != null) ? $detail->material->full_description:'No tiene',
                        'category' => ($detail->material_id == null || $detail->material->category_id == null ) ? 'No tiene' : $detail->material->category->name,
                        'quantity' => $detail->quantity,
                        'currency' => $order->currency_order,
                        'price_igv' => round((float)$detail->price, 2),
                        'price_sin_igv' => round((float)$detail->price/1.18, 2),
                        'total_igv' => ($detail->total_detail != null) ? $detail->total_detail : $total_con_igv,
                    ]);
                }

            }

            //dump($date_start);
            //dump($end_start);
        }
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Reporte Orden Compra Excel',
            'time' => $end
        ]);
        //dump($invoices_array);
        //dd('Fechas');
        //return response()->json(['message' => 'Reporte descargado correctamente.'], 200);
        //(new UsersExport)->download('users.xlsx');
        return (new ReportOrderPurchaseExport($orders_array, $dates))->download('ordenesCompra.xlsx');

    }
    

    public function pruebaCantidades()
    {
        // TODO: Cotizaciones activas y elevadas
        $quotesRaised = Quote::where('raise_status', 1)
            ->where('state_active', 'open')
            ->with('equipments')->get();

        // TODO: Arreglo de materiales y cantidad de materiales
        $materials = [];
        $materials_quantity = [];

        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            array_push($materials, $material->material_id);
                            //$urlQuote = '<a target="_blank" class="btn btn-primary btn-xs" href="'.route('quote.show', $quote->id).'" data-toggle="tooltip" data-placement="top" title="'.(float)$material->quantity*(float)$equipment->quantity.'">'.$quote->code.'</a>';
                            array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));
                        }

                    }
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

        dump($materials_quantity);

        // TODO: Nueva logica para hallar las cantidades
        $array_takens = [];
        foreach ( $quotesRaised as $quote )
        {
            foreach ( $quote->equipments as $equipment )
            {
                if ( !$equipment->finished )
                {
                    foreach ( $equipment->materials as $material )
                    {
                        // TODO: Reemplazo de materiales
                        if ( $material->replacement == 0 )
                        {
                            $materials_taken = MaterialTaken::where('equipment_id', $equipment->id)
                                ->where('material_id', $material->material_id)
                                ->where('type_output', 'orn')
                                ->get();

                            foreach ( $materials_taken as $item )
                            {
                                array_push($array_takens, array('material_id'=>$item->material_id, 'quantity'=> (float)$item->quantity_request));
                            }
                        }

                    }
                }

            }
        }

        $new_arr2 = array();
        foreach($array_takens as $item) {
            if(isset($new_arr2[$item['material_id']])) {
                $new_arr2[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr2[$item['material_id']] = $item;
        }

        $materials_takens = array_values($new_arr2);

        //dump($materials_takens);

        dump('Arreglo de materiales tomados');
        dump($materials_takens);

        $array_materials = [];
        //$array_takens = [];
        /*foreach ( $materials_quantity as $item )
        {
            $material_id = $item['material_id'];
            //dump($material_id);
            $cotizaciones = DB::table('equipments')
                ->join('equipment_materials', function ($join) {
                    $join->on('equipment_materials.equipment_id', '=', 'equipments.id');
                    //->where('contacts.user_id', '>', 5);
                })
                ->join('quotes', function ($join) {
                    $join->on('quotes.id', '=', 'equipments.quote_id')
                        ->where('quotes.state_active', '=', 'open')
                        ->where('quotes.raise_status', '=', 1);
                })
                ->where('equipments.finished', '=', 0)
                ->where('equipment_materials.material_id', '=', $material_id)
                ->select('equipments.quote_id')
                ->get()->pluck('quote_id');

            //dump($cotizaciones);
            $query = DB::table('material_takens')
                ->whereIn('material_takens.quote_id', $cotizaciones)
                ->where('material_takens.material_id', '=', $material_id)
                ->sum('quantity_request');

            //dump($query);

            array_push($array_takens, array('material_id'=>$material_id, 'quantity'=> (float)$query));

        }*/
        //dump('Arreglo de materiales tomados');
        //dump($array_takens);

        //dump($array_takens[array_search(91, array_column($array_takens, 'material_id'))]['quantity']);

        foreach ( $materials_quantity as $item )
        {
            if ($item['material_id'] == 3079)
            {
                dump('Logica  ' . $item['material_id'] );
                $cantidadEnCotizaciones = $item['quantity'];
                dump('CC  ' . $cantidadEnCotizaciones);
                $stockReal = $item['material_complete']->stock_current;
                dump('stock  ' . $stockReal);
                $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
                dump('orden  ' . $amount);
                $tengoReal = $stockReal + $amount;
                dump('TR  ' . $tengoReal);
                $materials_taken = (array_search($item['material_id'], array_column($materials_takens, 'material_id')) == null) ? 0: $materials_takens[array_search($item['material_id'], array_column($materials_takens, 'material_id'))]['quantity'];
                dump('taken  ' . $materials_taken);
                $faltaReal = $cantidadEnCotizaciones - $materials_taken;
                dump('FR  ' . $faltaReal);
                $balance = $faltaReal - $tengoReal;
                dump('bal  ' . $balance);
            } else {
                //dump('Logica  ' . $item['material_id'] );
                $cantidadEnCotizaciones = $item['quantity'];
                //dump('CC  ' . $cantidadEnCotizaciones);
                $stockReal = $item['material_complete']->stock_current;
                //dump('stock  ' . $stockReal);
                $amount = MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_request') - MaterialOrder::where('material_id', $item['material_id'])->sum('quantity_entered');
                //dump('orden  ' . $amount);
                $tengoReal = $stockReal + $amount;
                //dump('TR  ' . $tengoReal);
                $materials_taken = $array_takens[array_search($item['material_id'], array_column($array_takens, 'material_id'))]['quantity'];
                //dump('taken  ' . $materials_taken);
                $faltaReal = $cantidadEnCotizaciones - $materials_taken;
                //dump('FR  ' . $faltaReal);
                $balance = $faltaReal - $tengoReal;
                //dump('bal  ' . $balance);
            }


            if ( $balance > 0 )
            {
                array_push($array_materials, array('material_id'=>$item['material_id'], 'material'=>$item['material'], 'material_complete'=>$item['material_complete'], 'quantity'=> (float)$item['quantity'], 'missing_amount'=> $balance));
            }
        }
        dump('Arreglo de cantidades');
        dump($materials_quantity);
        dump('Arreglo de cantidades 2');
        dump($array_materials);

        $arrayMaterialsFinal = [];

        foreach ( $array_materials as $material )
        {
            $stringQuote = '';
            foreach ( $quotesRaised as $quote )
            {
                $quantity = 0;
                foreach ($quote->equipments as $equipment)
                {
                    if ( !$equipment->finished ) {
                        foreach ($equipment->materials as $material2) {
                            //dump($material2->material_id == $material['material_id']);
                            if ($material2->material_id == $material['material_id'] && $material2->replacement == 0) {
                                $quantity += $material2->quantity * $equipment->quantity;
                            }
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

        dump('Arreglo de etiquetas');
        dump($arrayMaterialsFinal);

    }

    public function pruebaBD()
    {
        /*
        SELECT OD.*
        FROM output_details OD
        INNER JOIN outputs O
        ON OD.output_id = O.id
        WHERE O.indicator <> 'or'*/
        //SELECT * FROM `output_details` WHERE id IN (33,34,35,36,37,40,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59)
        //dump('Obteniendo las salidas');
        $outputs = Output::with('details')
            ->where('indicator', '<>', 'or')
            ->where('id', '>', 1159)
            ->get();
        //dump('Cantidad de salidas' . count($outputs));
        //dump('Recorriendo las salidas');
        foreach ( $outputs as $output )
        {
            //dump('============');
            //dump($output->id);
            $quote = Quote::with('equipments')
                ->where('order_execution', $output->execution_order)
                ->first();
            foreach ( $output->details as $detail )
            {
                $idMaterial = 0;
                if ( $detail->material_id == null )
                {
                    $item = Item::find($detail->item_id);
                    $idMaterial = $item->material_id;
                } else {
                    $idMaterial = $detail->material_id;
                }
                $lo_tiene_una_sola_vez = $this->solounEquipoTieneEseMaterial($quote->id, $idMaterial);

                //dump('$lo_tiene_una_sola_vez   -'.$lo_tiene_una_sola_vez);
                //dump('FALSE    -'. ($lo_tiene_una_sola_vez !== null));
                if ($lo_tiene_una_sola_vez != null || $lo_tiene_una_sola_vez != '')
                {
                    //dump('material_id    '. ($detail->material_id));
                    //dump('FALSE    '. ($detail->material_id !== null));
                    if( $detail->material_id != null || $detail->material_id != '' )
                    {
                        // Detalle personalizado
                        if ( $detail->item_id == null )
                        {
                            $detail->quote_id = $quote->id;
                            $detail->equipment_id = $lo_tiene_una_sola_vez;
                            $detail->custom = 1;
                            $detail->save();
                        } else {
                            $item = Item::find($detail->item_id);

                            $detail->length = $item->length;
                            $detail->width = $item->width;
                            $detail->price = $item->price;
                            $detail->percentage = $item->percentage;
                            $detail->material_id = $item->material_id;
                            $detail->quote_id = $quote->id;
                            $detail->equipment_id = $lo_tiene_una_sola_vez;
                            $detail->custom = 1;
                            $detail->save();
                        }

                    } else {
                        $item = Item::find($detail->item_id);
                        $detail->length = $item->length;
                        $detail->width = $item->width;
                        $detail->price = $item->price;
                        $detail->percentage = $item->percentage;
                        $detail->material_id = $item->material_id;
                        $detail->quote_id = $quote->id;
                        $detail->equipment_id = $lo_tiene_una_sola_vez;
                        $detail->custom = 0;
                        $detail->save();
                    }
                } else {

                    // Logica que se usara para dos cosas
                    // 1. Cuando el material que encontro era una extra correcta y no hay
                    // 2. Cuando el material esta en dos equipos diferentes
                    //dump('Ingrese al if donde esta logica nueva');
                    $existeEquipo = $this->existeEquipo($quote->id, $idMaterial);
                    //dump('$existeEquipo  -' . $existeEquipo);
                    if ( $existeEquipo !== null || $existeEquipo !== ''  )
                    {
                        //dump('Entre al if1  -' . ($detail->material_id != null || $detail->material_id != ''));
                        if( $detail->material_id != null || $detail->material_id != '' )
                        {
                            //dump('Entre al if2');
                            //dump('IF $detail->item_id -'.( $detail->item_id == null ));
                            if ( $detail->item_id == null )
                            {
                                //dump('Entre al if3 ');
                                $detail->quote_id = $quote->id;
                                $detail->custom = 1;
                                $detail->equipment_id = $existeEquipo;
                                $detail->save();
                            } else {
                                //dump('Entre al else3 ');
                                $item = Item::find($detail->item_id);

                                $detail->length = $item->length;
                                $detail->width = $item->width;
                                $detail->price = $item->price;
                                $detail->percentage = $item->percentage;
                                $detail->material_id = $item->material_id;
                                $detail->quote_id = $quote->id;
                                $detail->equipment_id = $existeEquipo;
                                $detail->custom = 1;
                                $detail->save();
                            }
                        } else {
                            //dump('Entre al else1 ');
                            $item = Item::find($detail->item_id);
                            $detail->length = $item->length;
                            $detail->width = $item->width;
                            $detail->price = $item->price;
                            $detail->percentage = $item->percentage;
                            $detail->material_id = $item->material_id;
                            $detail->quote_id = $quote->id;
                            $detail->equipment_id = $existeEquipo;
                            $detail->custom = 0;
                            $detail->save();
                        }
                    } else {
                        //dump('No pude encontrar el equipo ');
                        if( $detail->material_id != null || $detail->material_id != '' )
                        {
                            if ( $detail->item_id == null )
                            {
                                $detail->quote_id = $quote->id;
                                $detail->custom = 1;
                                $detail->save();
                            } else {
                                $item = Item::find($detail->item_id);

                                $detail->length = $item->length;
                                $detail->width = $item->width;
                                $detail->price = $item->price;
                                $detail->percentage = $item->percentage;
                                $detail->material_id = $item->material_id;
                                $detail->quote_id = $quote->id;
                                $detail->custom = 1;
                                $detail->save();
                            }
                        } else {
                            $item = Item::find($detail->item_id);
                            $detail->length = $item->length;
                            $detail->width = $item->width;
                            $detail->price = $item->price;
                            $detail->percentage = $item->percentage;
                            $detail->material_id = $item->material_id;
                            $detail->quote_id = $quote->id;
                            $detail->custom = 0;
                            $detail->save();
                        }
                    }

                }
            }
        }

        //dump('Ahora los materiales tomados');
        $material_takens = MaterialTaken::all();
        //dump('Cantidad de registros materiales tomados');
        //dump(count($material_takens));
        foreach ( $material_takens as $material_taken )
        {
            $output = Output::find($material_taken->output_id);
            $output_detail = OutputDetail::where('output_id', $output->id)
                ->where('material_id', $material_taken->material_id)
                ->first();
            $material_taken->type_output = $output->indicator;
            $material_taken->equipment_id = $output_detail->equipment_id;
            $material_taken->save();
        }
    }

    public function solounEquipoTieneEseMaterial( $id_quote, $id_material )
    {
        $quote = Quote::with('equipments')->find($id_quote);
        //dump('COT-27-   '. $quote->id);
        $material2 = Material::find($id_material);
        //dump('MAT-1   '. $material2->id);
        $lo_tiene_una_sola_vez = false;
        $id_equipo = 0;
        foreach ( $quote->equipments as $equipment )
        {
            //dump('EQUIP-94   '. $equipment->id);
            foreach ( $equipment->materials as $material )
            {
                //dump('EQUIP_MAT-116   '. $material->id);
                //dump('TRUE   '. ($material->material_id == $material2->id && $material->replacement == 0));
                if ( $material->material_id == $material2->id && $material->replacement == 0 )
                {
                    //dump('Entre al if');
                    if ( $id_equipo == 0 && $lo_tiene_una_sola_vez == false)
                    {
                        //dump('Entre al 2° if');
                        $id_equipo = $equipment->id;
                        //dump('ID_EQUIPO   '.$id_equipo);
                        $lo_tiene_una_sola_vez = true;
                        //dump('$lo_tiene_una_sola_vez   '.$lo_tiene_una_sola_vez);
                    } else {
                        //dump('Entre al else');
                        if ( $id_equipo != $equipment->id )
                        {
                            //dump('Entre al break');
                            $lo_tiene_una_sola_vez = false;
                            break 2;
                        }
                    }
                }
            }
            foreach ( $equipment->consumables as $material )
            {
                //dump('EQUIP_MAT-116   '. $material->id);
                //dump('TRUE   '. ($material->material_id == $material2->id && $material->replacement == 0));
                if ( $material->material_id == $material2->id && $material->replacement == 0 )
                {
                    //dump('Entre al if');
                    if ( $id_equipo == 0 && $lo_tiene_una_sola_vez == false)
                    {
                        //dump('Entre al 2° if');
                        $id_equipo = $equipment->id;
                        //dump('ID_EQUIPO   '.$id_equipo);
                        $lo_tiene_una_sola_vez = true;
                        //dump('$lo_tiene_una_sola_vez   '.$lo_tiene_una_sola_vez);
                    } else {
                        //dump('Entre al else');
                        if ( $id_equipo != $equipment->id )
                        {
                            //dump('Entre al break');
                            $lo_tiene_una_sola_vez = false;
                            break 2;
                        }
                    }
                }
            }
        }
        //dump('Retorno?    '.$lo_tiene_una_sola_vez);
        if ($lo_tiene_una_sola_vez)
        {
            //dump('Retorné    '.$id_equipo);
            return $id_equipo;
        } else {
            //dump('Retorné    null');
            return null;
        }

    }

    public function existeEquipo( $id_quote, $id_material )
    {
        $quote = Quote::with('equipments')->find($id_quote);
        //dump('COT-   '. $quote->id);
        $material2 = Material::find($id_material);
        //dump('MAT   '. $material2->id);
        $existeEquipo = false;
        $id_equipo = 0;
        foreach ( $quote->equipments as $equipment )
        {
            //dump('EQUIP-   '. $equipment->id);
            foreach ( $equipment->materials as $material )
            {
                //dump('EQUIP_MAT-   '. $material->id);
                //dump('TRUE   '. ($material->material_id == $material2->id && $material->replacement == 0));
                if ( $material->material_id == $material2->id && $material->replacement == 0 )
                {
                    //dump('Entre al if');
                    $id_equipo = $equipment->id;
                    //dump('ID_EQUIPO   '.$id_equipo);
                    $existeEquipo = true;
                    //dump('$lo_tiene_una_sola_vez   '.$existeEquipo);
                    //dump('Entre al if');
                    break 2;
                }
            }
            foreach ( $equipment->consumables as $material )
            {
                //dump('EQUIP_MAT   '. $material->id);
                //dump('TRUE   '. ($material->material_id == $material2->id && $material->replacement == 0));
                if ( $material->material_id == $material2->id && $material->replacement == 0 )
                {
                    //dump('Entre al if');
                    $id_equipo = $equipment->id;
                    //dump('ID_EQUIPO   '.$id_equipo);
                    $existeEquipo = true;
                    //dump('$lo_tiene_una_sola_vez   '.$existeEquipo);
                    break 2;
                }
            }
        }
        //dump('Retorno?    '.$existeEquipo);
        if ($existeEquipo)
        {
            //dump('Retorné    '.$id_equipo);
            return $id_equipo;
        } else {
            //dump('Retorné    null');
            return null;
        }

    }

    public function onlyZeros($cadena) {
        $cadenaSinGuiones = str_replace('-', '', $cadena); // Eliminar los guiones

        if (!ctype_digit($cadenaSinGuiones)) {
            return false; // La cadena contiene caracteres que no son dígitos
        }

        if ($cadenaSinGuiones !== str_repeat('0', strlen($cadenaSinGuiones))) {
            return false; // La cadena no está formada solo por ceros
        }

        return true; // La cadena está formada solo por ceros
    }
    /*
     * crear una cotizacion con dos equi
     */
}
