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
use App\SupplierCredit;
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

        return view('orderPurchaseFinance.indexOrderFinance', compact('permissions'));
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
        $codeOrder = 'OCF-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $unitMeasures = UnitMeasure::select(['id', 'description'])->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear Orden compra Normal VISTA',
            'time' => $end
        ]);
        return view('orderPurchaseFinance.createOrderFinance', compact('users', 'codeOrder', 'suppliers', 'payment_deadlines', 'unitMeasures'));

    }

    public function storeOrderPurchaseFinance(StoreOrderPurchaseFinanceRequest $request)
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

            $orderPurchaseFinance = OrderPurchaseFinance::create([
                'code' => '',
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_delivery' => ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('service_condition')) ? $request->get('service_condition') : '',
                'currency_order' => ($request->get('currency') === 'true') ? 'PEN': 'USD',
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'observation' => $request->get('observation'),
                'quote_supplier' => $request->get('quote_supplier'),
                'regularize' => ($request->get('regularize') === 'true') ? 'r':'nr',
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
            ]);

            $codeOrder = '';
            if ( $maxId < $orderPurchaseFinance->id ){
                $codeOrder = 'OCF-'.str_pad($orderPurchaseFinance->id,$length,"0", STR_PAD_LEFT);
                $orderPurchaseFinance->code = $codeOrder;
                $orderPurchaseFinance->save();
            } else {
                $codeOrder = 'OCF-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderPurchaseFinance->code = $codeOrder;
                $orderPurchaseFinance->save();
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseFinanceDetail = OrderPurchaseFinanceDetail::create([
                    'order_purchase_finance_id' => $orderPurchaseFinance->id,
                    'material' => $items[$i]->service,
                    'unit' => $items[$i]->unit,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                $total = $orderPurchaseFinanceDetail->total_detail;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderPurchaseFinanceDetail->igv = $igv;
                $orderPurchaseFinanceDetail->save();

            }

            // Si el plazo indica credito, se crea el credito
            if ( isset($orderPurchaseFinance->deadline) )
            {
                if ( $orderPurchaseFinance->deadline->credit == 1 || $orderPurchaseFinance->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderPurchaseFinance->deadline->id);
                    //$fecha_issue = Carbon::parse($orderService->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderPurchaseFinance->supplier->id,
                        'total_soles' => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:null,
                        'total_dollars' => ($orderPurchaseFinance->currency_order == 'USD') ? $orderPurchaseFinance->total:null,
                        //'date_issue' => $orderService->date_order,
                        'order_purchase_id' => null,
                        'state_credit' => 'outstanding',
                        'order_service_id' => null,
                        'order_purchase_finance_id' => $orderPurchaseFinance->id,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderPurchaseFinance->code,
                        'payment_deadline_id' => $orderPurchaseFinance->payment_deadline_id
                    ]);
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar Orden Purchase Finance',
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
        $details = OrderPurchaseFinanceDetail::where('order_purchase_finance_id', $order->id)->get();

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Editar Orden de compra finance VISTA',
            'time' => $end
        ]);
        return view('orderPurchaseFinance.editOrderPurchaseFinance', compact('order', 'details', 'suppliers', 'users', 'unitMeasures', 'payment_deadlines'));

    }

    public function updateOrderPurchaseFinance(StoreOrderPurchaseFinanceRequest $request)
    {
        $begin = microtime(true);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderFinance = OrderPurchaseFinance::find($request->get('order_id'));
            $orderFinance->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderFinance->date_delivery = ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now();
            $orderFinance->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderFinance->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderFinance->payment_condition = ($request->has('service_condition')) ? $request->get('service_condition') : '';
            $orderFinance->currency_order = ($request->get('state') === 'true') ? 'PEN': 'USD';
            $orderFinance->igv = (float) $request->get('taxes_send');
            $orderFinance->total = (float) $request->get('total_send');
            $orderFinance->observation = $request->get('observation');
            $orderFinance->quote_supplier = $request->get('quote_supplier');
            $orderFinance->regularize = ($request->get('regularize') === 'true') ? 'r': 'nr';
            $orderFinance->payment_deadline_id = ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null;
            $orderFinance->save();

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                if ($items[$i]->detail_id === '')
                {
                    $orderFinanceDetail = OrderPurchaseFinanceDetail::create([
                        'order_purchase_finance_id' => $orderFinance->id,
                        'material' => $items[$i]->service,
                        'unit' => $items[$i]->unit,
                        'quantity' => (float) $items[$i]->quantity,
                        'price' => (float) $items[$i]->price,
                        'total_detail' => (float) $items[$i]->total,
                    ]);

                    $total = round($orderFinanceDetail->total_detail, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = round($total - $subtotal, 2);
                    $orderFinanceDetail->igv = $igv;
                    $orderFinanceDetail->save();
                }

            }
            // Si la orden de servicio se modifica, el credito tambien se modificara
            $credit = SupplierCredit::where('order_purchase_finance_id', $orderFinance->id)
                ->where('state_credit', 'outstanding')->first();
            if ( isset($credit) )
            {
                $deadline = PaymentDeadline::find($credit->deadline->id);
                //$fecha_issue = Carbon::parse($orderService->date_order);
                //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                $credit->supplier_id = $orderFinance->supplier->id;
                $credit->total_soles = ($orderFinance->currency_order == 'PEN') ? $orderFinance->total:null;
                $credit->total_dollars = ($orderFinance->currency_order == 'USD') ? $orderFinance->total:null;
                //$credit->date_issue = $orderService->date_order;
                $credit->code_order = $orderFinance->code;
                //$credit->date_expiration = $fecha_expiration;
                //$credit->days_to_expiration = $dias_to_expire;
                $credit->payment_deadline_id = $orderFinance->payment_deadline_id;
                $credit->save();
            }

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
            $orderPurchaseFinance = OrderPurchaseFinance::find($detail->order_purchase_finance_id);
            $orderPurchaseFinance->igv = $orderPurchaseFinance->igv - $detail->igv;
            $orderPurchaseFinance->total = $orderPurchaseFinance->total - ($detail->total_detail);
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
            $orderPurchase = OrderPurchaseFinance::find($detail->order_purchase_finance_id);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {

                $total_last = $detail->total_detail;
                $igv_last = $detail->igv;

                $quantity = (float) $items[$i]->quantity;
                $price = (float) $items[$i]->price;
                $totalD = (float) $items[$i]->total;

                $total = round($totalD, 2);
                $subtotal = round($total / 1.18, 2);
                $igv = $total - $subtotal;

                $detail->quantity = round($quantity, 2);
                $detail->price = round($price, 2);
                $detail->igv = round($igv,2);
                $detail->total_detail = round($totalD,2);
                $detail->save();

                $orderPurchase->igv = round(($orderPurchase->igv - $igv_last),2);
                $orderPurchase->total = round(($orderPurchase->total - $total_last),2);
                $orderPurchase->save();

                $orderPurchase->igv = round(($orderPurchase->igv + $igv),2);
                $orderPurchase->total = round(($orderPurchase->total + $total),2);

                $orderPurchase->save();

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
        return view('orderPurchaseFinance.showOrderPurchaseFinance', compact('order', 'details', 'suppliers', 'users'));

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

        return view('orderPurchaseFinance.indexFinanceDeleted', compact('permissions'));
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
            ->get();

        return view('orderPurchaseFinance.showOrderPurchaseDeleteFinance', compact('order', 'details', 'suppliers', 'users'));

    }

    public function printOrderPurchaseFinanceDelete($id)
    {
        $service_order = null;
        $service_order = OrderPurchaseFinance::withTrashed()
            ->with('approved_user')
            ->with('deadline')
            ->with(['details'])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.orderFinance', compact('service_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_finanzas_ ' . $service_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function printOrderPurchaseFinance($id)
    {
        $purchase_order = null;
        $service_order = OrderPurchaseFinance::with('approved_user')
            ->with('deadline')
            ->with(['details'])
            ->where('id', $id)->first();

        $length = 5;
        $codeOrder = ''.str_pad($id,$length,"0", STR_PAD_LEFT);

        $view = view('exports.orderFinance', compact('service_order','codeOrder'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_compra_finanzas_ ' . $service_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function restoreOrderPurchaseFinanceDelete($id)
    {
        $begin = microtime(true);
        $orderPurchase = OrderPurchaseFinance::onlyTrashed()->find($id);

        $details = OrderPurchaseFinanceDetail::onlyTrashed()
            ->where('order_purchase_finance_id', $id)->get();
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

        return view('orderPurchaseFinance.indexRegularizeFinance', compact('permissions'));
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

        return view('orderPurchaseFinance.indexFinanceLost', compact('permissions'));
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
                $codeOrder = 'OCF-'.str_pad($iterator,5,"0", STR_PAD_LEFT);
                array_push($lost, ['code'=>$codeOrder]);
                $iterator++;
            }
            $iterator++;
        }
        //dd($lost);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Orden de compras finanzas Perdidas',
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
        $codeOrder = 'OCF-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $details = DetailEntry::where('entry_id', $entry_id)->get();
        //dd($entry);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Regularizar Orden de compra de finanzas VISTA',
            'time' => $end
        ]);
        return view('orderPurchaseFinance.regularizeAutoEntryPurchaseFinance', compact('entry', 'details', 'suppliers', 'users', 'unitMeasures', 'codeOrder', 'payment_deadlines'));
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

            $orderPurchaseFinance = OrderPurchaseFinance::create([
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

            if ( $maxId < $orderPurchaseFinance->id ){
                $codeOrder = 'OCF-'.str_pad($orderPurchaseFinance->id,$length,"0", STR_PAD_LEFT);
                $orderPurchaseFinance->code = $codeOrder;
                $orderPurchaseFinance->save();
            } else {
                $codeOrder2 = 'OCF-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderPurchaseFinance->code = $codeOrder2;
                $orderPurchaseFinance->save();
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseFinanceDetail = OrderPurchaseFinanceDetail::create([
                    'order_purchase_finance_id' => $orderPurchaseFinance->id,
                    'material' => $items[$i]->service,
                    'unit' => $items[$i]->unit,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                $total = $orderPurchaseFinanceDetail->total_detail;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderPurchaseFinanceDetail->igv = $igv;
                $orderPurchaseFinanceDetail->save();

            }

            // TODO: Modificamos la orden de servicio
            $entry = Entry::find($request->get('entry_id'));

            $orderPurchaseFinance->invoice = $entry->invoice;
            $orderPurchaseFinance->referral_guide = $entry->referral_guide;
            $orderPurchaseFinance->date_invoice = $entry->date_entry;
            $orderPurchaseFinance->save();

            $entry->purchase_order = $orderPurchaseFinance->code;
            $entry->save();

            // TODO: Tratamiento de imagenes
            /*if ($entry->image != null)
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

            }*/


            // Si el plazo indica credito, se crea el credito
            if ( isset($orderPurchaseFinance->deadline) )
            {
                if ( $orderPurchaseFinance->deadline->credit == 1 || $orderPurchaseFinance->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderPurchaseFinance->deadline->id);
                    //$fecha_issue = Carbon::parse($orderService->date_order);
                    //$fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    //$dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderPurchaseFinance->supplier->id,
                        'total_soles' => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:null,
                        'total_dollars' => ($orderPurchaseFinance->currency_order == 'USD') ? $orderPurchaseFinance->total:null,
                        //'date_issue' => $orderService->date_order,
                        'order_purchase_id' => null,
                        'state_credit' => 'outstanding',
                        'order_service_id' => null,
                        'order_purchase_finance_id' => $orderPurchaseFinance->id,
                        //'date_expiration' => $fecha_expiration,
                        //'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderPurchaseFinance->code,
                        'payment_deadline_id' => $orderPurchaseFinance->payment_deadline_id
                    ]);
                }
            }

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
        return response()->json(['message' => 'Orden de compra finanzas '.$orderPurchaseFinance->code.' guardada con éxito.', 'url' => route('report.invoice.finance.sin.orden')], 200);

    }

}
