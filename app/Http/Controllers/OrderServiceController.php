<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderServiceRequest;
use App\OrderService;
use App\OrderServiceDetail;
use App\PaymentDeadline;
use App\Supplier;
use App\UnitMeasure;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Intervention\Image\Facades\Image;

class OrderServiceController extends Controller
{
    public function indexOrderServices()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderService.indexOrderService', compact('permissions'));

    }

    public function listOrderServices()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderService.listOrderService', compact('permissions'));

    }

    public function createOrderServices()
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $unitMeasures = UnitMeasure::select(['id', 'description'])->get();

        $maxId = OrderService::max('id')+1;
        $length = 5;
        $codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderService.createOrderService', compact('users', 'codeOrder', 'suppliers', 'unitMeasures', 'payment_deadlines'));

    }

    public function storeOrderServices(StoreOrderServiceRequest $request)
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
            $orderService = OrderService::create([
                'code' => $request->get('service_order'),
                'quote_supplier' => $request->get('quote_supplier'),
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_delivery' => ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('service_condition')) ? $request->get('service_condition') : '',
                'currency_order' => ($request->has('currency_order')) ? 'PEN':'USD',
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'regularize' => ($request->has('regularize_order')) ? 'r':'nr',
            ]);

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderServiceDetail = OrderServiceDetail::create([
                    'order_service_id' => $orderService->id,
                    'service' => $items[$i]->service,
                    'unit' => $items[$i]->unit,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                ]);

                $total = $orderServiceDetail->quantity*$orderServiceDetail->price;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderServiceDetail->igv = $igv;
                $orderServiceDetail->save();

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de servicio guardada con éxito.'], 200);

    }

    public function showOrderService($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderService::with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderServiceDetail::where('order_service_id', $order->id)->get();

        return view('orderService.showOrderService', compact('order', 'details', 'suppliers', 'users'));

    }

    public function editOrderService($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();
        $unitMeasures = UnitMeasure::select(['id', 'description'])->get();

        $order = OrderService::with(['supplier', 'approved_user'])->find($id);
        $details = OrderServiceDetail::where('order_service_id', $order->id)->get();

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        return view('orderService.editOrderService', compact('order', 'details', 'suppliers', 'users', 'unitMeasures', 'payment_deadlines'));

    }

    public function updateOrderService(StoreOrderServiceRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderService = OrderService::find($request->get('order_id'));
            $orderService->supplier_id = ($request->has('supplier_id')) ? $request->get('supplier_id') : null;
            $orderService->payment_deadline_id = ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null;
            $orderService->date_delivery = ($request->has('date_delivery')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : Carbon::now();
            $orderService->date_order = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
            $orderService->approved_by = ($request->has('approved_by')) ? $request->get('approved_by') : null;
            $orderService->payment_condition = ($request->has('service_condition')) ? $request->get('service_condition') : '';
            $orderService->currency_order = ($request->get('state') === 'true') ? 'PEN': 'USD';
            $orderService->regularize = ($request->get('regularize') === 'true') ? 'r': 'nr';
            $orderService->observation = $request->get('observation');
            $orderService->quote_supplier = $request->get('quote_supplier');
            $orderService->igv = (float) $request->get('taxes_send');
            $orderService->total = (float) $request->get('total_send');
            $orderService->save();

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                if ($items[$i]->detail_id === '')
                {
                    $orderServiceDetail = OrderServiceDetail::create([
                        'order_service_id' => $orderService->id,
                        'service' => $items[$i]->service,
                        'unit' => $items[$i]->unit,
                        'quantity' => (float) $items[$i]->quantity,
                        'price' => (float) $items[$i]->price,
                    ]);

                    $total = round($orderServiceDetail->quantity*$orderServiceDetail->price, 2);
                    $subtotal = round($total / 1.18, 2);
                    $igv = round($total - $subtotal, 2);
                    $orderServiceDetail->igv = $igv;
                    $orderServiceDetail->save();
                }

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de servicio modificada con éxito.'], 200);

    }

    public function destroyOrderService($order_id)
    {
        $orderService = OrderService::find($order_id);
        $details = OrderServiceDetail::where('order_service_id', $orderService->id)->get();
        foreach ( $details as $detail )
        {
            $detail->delete();
        }
        $orderService->delete();

        return response()->json(['message' => 'Orden de servicio eliminada con éxito.'], 200);

    }

    public function getAllOrderService()
    {
        $orders = OrderService::with(['supplier', 'approved_user'])
            ->where('regularize', 'nr')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }

    public function printOrderService($id)
    {
        $service_order = null;
        $service_order = OrderService::with('approved_user')
            ->with('deadline')
            ->with('details')
            ->where('id', $id)->first();

        $view = view('exports.orderService', compact('service_order'));

        $pdf = PDF::loadHTML($view);

        $name = 'Orden_de_servicio_ ' . $service_order->id . '.pdf';

        return $pdf->stream($name);
    }

    public function updateDetail(Request $request, $detail_id)
    {
        DB::beginTransaction();
        try {
            $detail = OrderServiceDetail::find($detail_id);
            $orderService = OrderService::find($detail->order_service_id);

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

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle de servicio modificado con éxito.'], 200);

    }

    public function destroyDetail($idDetail)
    {
        DB::beginTransaction();
        try {
            $detail = OrderServiceDetail::find($idDetail);
            $orderService = OrderService::find($detail->order_service_id);
            $orderService->igv = $orderService->igv - $detail->igv;
            $orderService->total = $orderService->total - ($detail->quantity*$detail->price);
            $orderService->save();

            $detail->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Detalle de orden eliminado con éxito.'], 200);

    }

    public function regularizeOrderService($id)
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $order = OrderService::with(['supplier', 'approved_user', 'deadline'])->find($id);
        $details = OrderServiceDetail::where('order_service_id', $order->id)->get();

        return view('orderService.regularizeOrderService', compact('order', 'details', 'suppliers', 'users'));

    }

    public function regularizePostOrderService( Request $request )
    {
        DB::beginTransaction();
        try {
            $orderService = OrderService::find($request->get('service_order_id'));
            $orderService->deferred_invoice = ($request->get('deferred_invoice') === 'true') ? 'on': 'off';
            $orderService->observation = $request->get('observation');
            $orderService->date_invoice = ($request->has('date_invoice')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_invoice')) : Carbon::now();
            $orderService->referral_guide = $request->get('referral_guide');
            $orderService->invoice = $request->get('invoice');
            $orderService->regularize = 'r';
            $orderService->save();

            if (!$request->file('image')) {
                if ($orderService->image_invoice == 'no_image.png' || $orderService->image_invoice == null) {
                    $orderService->image_invoice = 'no_image.png';
                    $orderService->save();
                }
            } else {
                $path = public_path().'/images/orderServices/';
                $image = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                $filename = $orderService->id . '.jpg';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'jpg');
                //$request->file('image')->move($path, $filename);
                $orderService->image_invoice = $filename;
                $orderService->save();
            }

            if (!$request->file('imageOb')) {
                if ($orderService->image_observation == 'no_image.png' || $orderService->image_observation == null) {
                    $orderService->image_observation = 'no_image.png';
                    $orderService->save();
                }
            } else {
                $path = public_path().'/images/orderServices/observations/';
                $image = $request->file('imageOb');
                $extension = $image->getClientOriginalExtension();
                $filename = $orderService->id . '.jpg';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'jpg');
                //$request->file('image')->move($path, $filename);
                $orderService->image_observation = $filename;
                $orderService->save();
            }


            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Orden de servicio modificada con éxito.'], 200);

    }

    public function indexServices()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderService.indexService', compact('permissions'));

    }

    public function getAllOrderRegularizeService()
    {
        $orders = OrderService::with(['supplier', 'approved_user'])
            ->where('regularize', 'r')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($orders)->toJson();
    }
}
