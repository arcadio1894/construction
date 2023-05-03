<?php

namespace App\Http\Controllers;

use App\Audit;
use App\DetailEntry;
use App\Entry;
use App\Http\Requests\StoreOrderPurchaseFinanceRequest;
use App\Http\Requests\UpdateOrderPurchaseFinanceRequest;
use App\OrderPurchaseFinance;
use App\OrderPurchaseFinanceDetail;
use App\PaymentDeadline;
use App\Supplier;
use App\UnitMeasure;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class OrderPurchaseFinanceController extends Controller
{
    public function indexOrderPurchaseFinance()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexFinance', compact('permissions'));
    }

    public function createOrderPurchaseFinance()
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();

        // TODO: WITH TRASHED
        $maxCode = OrderPurchaseFinance::withTrashed()->max('id');
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
        return view('orderPurchase.createFinance', compact('users', 'codeOrder', 'suppliers', 'payment_deadlines'));

    }

    public function storeOrderPurchaseNormal(StoreOrderPurchaseFinanceRequest $request)
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
            $maxId = OrderPurchaseFinance::withTrashed()->max('id')+1;
            $length = 5;
            //$codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderService = OrderPurchaseFinance::create([
                'code' => '',
                'quote_supplier' => $request->get('quote_supplier'),
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_delivery' => ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('service_condition')) ? $request->get('service_condition') : '',
                'currency_order' => ($request->get('state') === 'true') ? 'PEN': 'USD',
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'regularize' => ($request->get('regularize') === 'true') ? 'r':'nr',
            ]);

            $codeOrder = '';
            if ( $maxId < $orderService->id ){
                $codeOrder = 'OS-'.str_pad($orderService->id,$length,"0", STR_PAD_LEFT);
                $orderService->code = $codeOrder;
                $orderService->save();
            } else {
                $codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderService->code = $codeOrder;
                $orderService->save();
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderServiceDetail = OrderPurchaseFinanceDetail::create([
                    'order_service_id' => $orderService->id,
                    'service' => $items[$i]->service,
                    'unit' => $items[$i]->unit,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                $total = $orderServiceDetail->total_detail;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderServiceDetail->igv = $igv;
                $orderServiceDetail->save();

            }

            // Si el plazo indica credito, se crea el credito
            /*if ( isset($orderService->deadline) )
            {
                if ( $orderService->deadline->credit == 1 || $orderService->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderService->deadline->id);
                    //$fecha_issue = Carbon::parse($orderService->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderService->supplier->id,
                        'total_soles' => ($orderService->currency_order == 'PEN') ? $orderService->total:null,
                        'total_dollars' => ($orderService->currency_order == 'USD') ? $orderService->total:null,
                        //'date_issue' => $orderService->date_order,
                        'order_purchase_id' => null,
                        'state_credit' => 'outstanding',
                        'order_service_id' => $orderService->id,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderService->code,
                        'payment_deadline_id' => $orderService->payment_deadline_id
                    ]);
                }
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar Orden Servicio',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de compra de finanzas '.$codeOrder.' guardada con éxito.'], 200);

    }

    public function getAllOrderFinance()
    {
        $orders = OrderPurchaseFinance::with(['supplier', 'approved_user'])
            /*->where('regularize', 'nr')*/
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function editOrderPurchaseFinance($id)
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();
        $unitMeasures = UnitMeasure::select(['id', 'description'])->get();

        $order = OrderPurchaseFinance::with(['supplier', 'approved_user'])->find($id);
        $details = OrderPurchaseFinanceDetail::where('order_service_id', $order->id)->get();

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Editar Orden de compra finance VISTA',
            'time' => $end
        ]);
        return view('orderPurchase.editOrderPurchaseFinance', compact('order', 'details', 'suppliers', 'users', 'unitMeasures', 'payment_deadlines'));

    }

    public function updateOrderPurchaseFinance(UpdateOrderPurchaseFinanceRequest $request)
    {
        $begin = microtime(true);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderFinance = OrderPurchaseFinance::find($request->get('order_id'));
            $orderFinance->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderFinance->payment_deadline_id = ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null;
            $orderFinance->date_delivery = ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now();
            $orderFinance->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderFinance->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderFinance->payment_condition = ($request->has('service_condition')) ? $request->get('service_condition') : '';
            $orderFinance->currency_order = ($request->get('state') === 'true') ? 'PEN': 'USD';
            $orderFinance->regularize = ($request->get('regularize') === 'true') ? 'r': 'nr';
            $orderFinance->observation = $request->get('observation');
            $orderFinance->quote_supplier = $request->get('quote_supplier');
            $orderFinance->igv = (float) $request->get('taxes_send');
            $orderFinance->total = (float) $request->get('total_send');
            $orderFinance->save();

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                if ($items[$i]->detail_id === '')
                {
                    $orderFinanceDetail = OrderPurchaseFinanceDetail::create([
                        'order_service_id' => $orderFinance->id,
                        'service' => $items[$i]->service,
                        'unit' => $items[$i]->unit,
                        'quantity' => (float) $items[$i]->quantity,
                        'price' => (float) $items[$i]->price,
                    ]);

                    $total = round($orderFinanceDetail->quantity*$orderFinanceDetail->price, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = round($total - $subtotal, 2);
                    $orderFinanceDetail->igv = $igv;
                    $orderFinanceDetail->save();
                }

            }
            // Si la orden de servicio se modifica, el credito tambien se modificara
            /*$credit = SupplierCredit::where('order_service_id', $orderService->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $deadline = PaymentDeadline::find($credit->deadline->id);
                //$fecha_issue = Carbon::parse($orderService->date_order);
                //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                $credit->supplier_id = $orderService->supplier->id;
                $credit->total_soles = ($orderService->currency_order == 'PEN') ? $orderService->total:null;
                $credit->total_dollars = ($orderService->currency_order == 'USD') ? $orderService->total:null;
                //$credit->date_issue = $orderService->date_order;
                $credit->code_order = $orderService->code;
                //$credit->date_expiration = $fecha_expiration;
                //$credit->days_to_expiration = $dias_to_expire;
                $credit->payment_deadline_id = $orderService->payment_deadline_id;
                $credit->save();
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Modificar Orden compra de Finanzas',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de compra de Finanzas modificada con éxito.'], 200);

    }

    public function destroyFinanceDetail($idDetail)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $detail = OrderPurchaseFinanceDetail::find($idDetail);
            $orderPurchaseFinance = OrderPurchaseFinance::find($detail->order_service_id);
            $orderPurchaseFinance->igv = $orderPurchaseFinance->igv - $detail->igv;
            $orderPurchaseFinance->total = $orderPurchaseFinance->total - ($detail->quantity*$detail->price);
            $orderPurchaseFinance->save();

            // Si la orden de compra express se modifica, el credito tambien se modificara
            /*$credit = SupplierCredit::where('order_service_id', $orderService->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $credit->total_soles = ($orderService->currency_order == 'PEN') ? $orderService->total:null;
                $credit->total_dollars = ($orderService->currency_order == 'USD') ? $orderService->total:null;
                $credit->save();
            }*/

            $detail->delete();

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar Orden de COmpra Finanzas Detalle',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle de orden eliminado con éxito.'], 200);

    }

    public function updateFinanceDetail(Request $request, $detail_id)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $detail = OrderPurchaseFinanceDetail::find($detail_id);
            $orderService = OrderPurchaseFinance::find($detail->order_service_id);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {

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

                $orderService->igv = round(($orderService->igv - $igv_last),2);
                $orderService->total = round(($orderService->total - $total_last),2);
                $orderService->save();

                $orderService->igv = round(($orderService->igv + $igv),2);
                $orderService->total = round(($orderService->total + $total),2);

                $orderService->save();

                // Si la orden de compra express se modifica, el credito tambien se modificara
                /*$credit = SupplierCredit::where('order_service_id', $orderService->id)
                    ->where('state_credit', 'outstanding')->first();
                if ( isset($credit) )
                {
                    $credit->total_soles = ($orderService->currency_order == 'PEN') ? $orderService->total:null;
                    $credit->total_dollars = ($orderService->currency_order == 'USD') ? $orderService->total:null;
                    $credit->save();
                }*/
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Modificar Orden de compra finanza Detalle',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle de orden modificado con éxito.'], 200);

    }

    public function showOrderPurchaseFinance($id)
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchaseFinance::with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderPurchaseFinanceDetail::where('order_purchase_finance_id', $order->id)->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver Orden de compra finanzass',
            'time' => $end
        ]);
        return view('orderPurchase.showOrderPurchaseFinance', compact('order', 'details', 'suppliers', 'users'));

    }

    public function destroyOrderPurchaseFinance($order_id)
    {
        $begin = microtime(true);
        $orderPurchaseFinance = OrderPurchaseFinance::find($order_id);
        $details = OrderPurchaseFinanceDetail::where('order_purchase_finance_id', $orderPurchaseFinance->id)->get();
        foreach ( $details as $detail )
        {
            $detail->delete();
        }

        // Si la orden de servicio se elimina, y el credito es pendiente se debe eliminar
        /*$credit = SupplierCredit::where('order_service_id', $orderService->id)
            ->where('state_credit', 'outstanding')->first();
        if ( isset($credit) )
        {
            $credit->delete();
        }*/

        $orderPurchaseFinance->delete();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Eliminar Orden de compra finanza',
            'time' => $end
        ]);
        return response()->json(['message' => 'Orden de compra de finanzas eliminada con éxito.'], 200);

    }

    public function changeStatusOrderPurchaseFinance($order_id, $status)
    {
        DB::beginTransaction();
        try {

            $orderPurchase = OrderPurchaseFinance::find($order_id);
            $orderPurchase->status_order = $status;
            $orderPurchase->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Estado modificado.'], 200);

    }

    public function indexOrderPurchaseFinanceDelete()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexFinanceDeleted', compact('permissions'));
    }

    public function getOrderDeleteFinance()
    {
        $orders = OrderPurchaseFinance::onlyTrashed()
            ->with(['supplier', 'approved_user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function showOrderPurchaseFinanceDelete($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderPurchaseFinance::withTrashed()
            ->with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderPurchaseFinanceDetail::withTrashed()
            ->where('order_purchase_finance_id', $order->id)
            ->with(['material'])->get();

        return view('orderPurchase.showDeleteFinance', compact('order', 'details', 'suppliers', 'users'));

    }

    public function printOrderPurchaseFinanceDelete($id)
    {
        $purchase_order = null;
        $purchase_order = OrderPurchaseFinance::withTrashed()
            ->with('approved_user')
            ->with('deadline')
            ->with(['details'])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.entryPurchaseFinance', compact('purchase_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_finanzas_ ' . $purchase_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function printOrderPurchaseFinance($id)
    {
        $purchase_order = null;
        $purchase_order = OrderPurchaseFinance::with('approved_user')
            ->with('deadline')
            ->with(['details'])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.entryPurchaseFinance', compact('purchase_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_finanzas_ ' . $purchase_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function restoreOrderPurchaseFinanceDelete($id)
    {
        $begin = microtime(true);
        $orderPurchase = OrderPurchaseFinance::onlyTrashed()->find($id);

        $details = OrderPurchaseFinanceDetail::onlyTrashed()
            ->where('order_purchase_id', $id)->get();
        foreach ( $details as $detail )
        {
            $detail->restore();
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Restaurar orden de compra',
            'time' => $end
        ]);
        $orderPurchase->restore();

    }

    public function indexOrderPurchaseFinanceRegularize()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexRegularizeFinanze', compact('permissions'));
    }

    public function getAllOrderRegularizeFinance()
    {
        $orders = OrderPurchaseFinance::with(['supplier', 'approved_user'])
            ->where('regularize', 'r')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function indexOrderPurchaseFinanceLost()
    {
        //$orders = OrderPurchase::with(['supplier', 'approved_user'])->get();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderPurchase.indexFinanceLost', compact('permissions'));
    }

    public function getAllOrderPurchaseFinanceLost()
    {
        $begin = microtime(true);
        $orders = OrderPurchaseFinance::withTrashed()
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
        for ( $j=0; $j< count($ids); ++$j )
        {
            while( $iterator < $ids[$j] )
            {
                $codeOrder = 'OS-'.str_pad($iterator,5,"0", STR_PAD_LEFT);
                array_push($lost, ['code'=>$codeOrder]);
                $iterator++;
            }
            $iterator++;
        }
        //dd($lost);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Orden Servicio Perdidas',
            'time' => $end
        ]);
        return datatables($lost)->toJson();
    }

    public function regularizeAutoOrderEntryPurchaseFinance( $entry_id )
    {
        $begin = microtime(true);
        $entry = Entry::find($entry_id);
        $suppliers = Supplier::all();
        $users = User::all();

        $unitMeasures = UnitMeasure::select(['id', 'description'])->get();

        $maxId = OrderPurchaseFinance::withTrashed()->max('id')+1;
        $length = 5;
        $codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $details = DetailEntry::where('entry_id', $entry_id)->get();
        //dd($entry);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Regularizar Orden de compra de finanzas VISTA',
            'time' => $end
        ]);
        return view('orderService.regularizeAutoEntryService', compact('entry', 'details', 'suppliers', 'users', 'unitMeasures', 'codeOrder', 'payment_deadlines'));
    }

    public function regularizeEntryToOrderPurchaseFinance(Request $request)
    {
        $begin = microtime(true);
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
            $maxId = OrderPurchaseFinance::withTrashed()->max('id')+1;
            $length = 5;
            //$codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderService = OrderPurchaseFinance::create([
                'code' => '',
                'quote_supplier' => $request->get('quote_supplier'),
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_delivery' => ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('service_condition')) ? $request->get('service_condition') : '',
                'currency_order' => ($request->get('state') === 'true') ? 'PEN': 'USD',
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'regularize' => ($request->get('regularize') === 'true') ? 'r':'nr',
            ]);

            if ( $maxId < $orderService->id ){
                $codeOrder = 'OS-'.str_pad($orderService->id,$length,"0", STR_PAD_LEFT);
                $orderService->code = $codeOrder;
                $orderService->save();
            } else {
                $codeOrder2 = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderService->code = $codeOrder2;
                $orderService->save();
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderServiceDetail = OrderPurchaseFinanceDetail::create([
                    'order_service_id' => $orderService->id,
                    'service' => $items[$i]->service,
                    'unit' => $items[$i]->unit,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                $total = $orderServiceDetail->total_detail;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderServiceDetail->igv = $igv;
                $orderServiceDetail->save();

            }

            // TODO: Modificamos la orden de servicio
            $entry = Entry::find($request->get('entry_id'));

            $orderService->invoice = $entry->invoice;
            $orderService->referral_guide = $entry->referral_guide;
            $orderService->date_invoice = $entry->date_entry;
            $orderService->save();

            $entry->purchase_order = $orderService->code;
            $entry->save();

            // TODO: Tratamiento de imagenes
            if ($entry->image != null)
            {
                if ( $entry->image != 'no_image.png' )
                {
                    $nombre = $entry->image;
                    $imagen = public_path().'/images/entries/'.$nombre;
                    $ruta = public_path().'/images/orderServices/';
                    $extension = substr($nombre, -3);
                    //$filename = $entry->id . '.' . $extension;
                    if (file_exists($imagen)) {
                        if ( strtoupper($extension) != "PDF" )
                        {
                            $filename = $orderService->id . '.JPG';
                            $img = Image::make($imagen);
                            $img->orientate();
                            $img->save($ruta.$filename, 80, 'JPG');
                            //$request->file('image')->move($path, $filename);
                            $orderService->image_invoice = $filename;
                            $orderService->save();
                        } else {
                            $filename = 'pdf'.$orderService->id . '.' .$extension;
                            $destino = $ruta.$filename;
                            copy($imagen, $destino);
                            //$request->file('image')->move($path, $filename);
                            $orderService->image_invoice = $filename;
                            $orderService->save();
                        }
                    }
                }

            }

            if ($entry->imageOb != null)
            {
                if ( $entry->imageOb != 'no_image.png' )
                {
                    $nombre = $entry->imageOb;
                    $imagen = public_path().'/images/entries/observations/'.$nombre;
                    $ruta = public_path().'/images/orderServices/observations/';
                    $extension = substr($nombre, -3);
                    //$filename = $entry->id . '.' . $extension;
                    if (file_exists($imagen)) {
                        if ( strtoupper($extension) != "PDF" )
                        {
                            $filename = $orderService->id . '.JPG';
                            $img = Image::make($imagen);
                            $img->orientate();
                            $img->save($ruta.$filename, 80, 'JPG');
                            //$request->file('image')->move($path, $filename);
                            $orderService->image_observation = $filename;
                            $orderService->save();
                        } else {
                            $filename = 'pdf'.$orderService->id . '.' .$extension;
                            $destino = $ruta.$filename;
                            copy($imagen, $destino);
                            //$request->file('image')->move($path, $filename);
                            $orderService->image_observation = $filename;
                            $orderService->save();
                        }
                    }
                }

            }


            // Si el plazo indica credito, se crea el credito
            /*if ( isset($orderService->deadline) )
            {
                if ( $orderService->deadline->credit == 1 || $orderService->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderService->deadline->id);
                    //$fecha_issue = Carbon::parse($orderService->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderService->supplier->id,
                        'total_soles' => ($orderService->currency_order == 'PEN') ? $orderService->total:null,
                        'total_dollars' => ($orderService->currency_order == 'USD') ? $orderService->total:null,
                        //'date_issue' => $orderService->date_order,
                        'order_purchase_id' => null,
                        'state_credit' => 'outstanding',
                        'order_service_id' => $orderService->id,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderService->code,
                        'payment_deadline_id' => $orderService->payment_deadline_id
                    ]);
                }
            }*/

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Regularizar Orden De Compra Finanza POST',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de compra finanzas '.$orderService->code.' guardada con éxito.', 'url' => route('invoice.index')], 200);

    }

}
