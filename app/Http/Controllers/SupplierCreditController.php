<?php

namespace App\Http\Controllers;

use App\CreditPay;
use App\Entry;
use App\OrderPurchase;
use App\OrderPurchaseFinance;
use App\OrderService;
use App\SupplierCredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class SupplierCreditController extends Controller
{
    public function indexCredits()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('credit.indexSupplierCredit', compact('permissions'));
    }

    public function getOnlyInvoicesPurchase()
    {
        $entries = Entry::with('supplier')
            ->doesntHave('credit')
            ->where('type_order', 'purchase')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($entries)->toJson();
    }

    public function getOnlyCreditsSupplier()
    {
        $credits = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($credits as $credit) {
            if (isset($credit->date_expiration) && $credit->state_credit != 'paid_out') {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ((int)$dias_to_expire < 4 && (int)$dias_to_expire > 0) {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ($dias_to_expire == 0) {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }
            }

        }
        $credits = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($credits)->toJson();
    }

    public function addInvoiceToCredit($idEntry)
    {
        DB::beginTransaction();
        try {
            $entry = Entry::with('supplier')->find($idEntry);

            $credit = SupplierCredit::create([
                'supplier_id' => $entry->supplier->id,
                'entry_id' => $entry->id,
                'invoice' => $entry->invoice,
                'image_invoice' => $entry->image,
                'purchase_order' => $entry->purchase_order,
                'total_soles' => ($entry->currency_invoice == 'PEN') ? $entry->total:null,
                'total_dollars' => ($entry->currency_invoice == 'USD') ? $entry->total:null,
                'date_issue' => $entry->date_entry,
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Factura agregada con éxito.'], 200);

    }

    public function getCreditById( $credit_id )
    {
        $credit = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->find($credit_id);

        return response()->json(['credit' => $credit], 200);
    }

    public function edit(Credit $credit)
    {
        //
    }

    public function update(Request $request)
    {
        //dd($request);
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($request->get('credit_id'));
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados
            $credit->date_issue = Carbon::createFromFormat('d/m/Y', $request->get('date_issue'), 'America/Lima');
            $credit->date_expiration = Carbon::createFromFormat('d/m/Y', $request->get('date_expiration'), 'America/Lima' );
            $credit->days_to_expiration = (int) $request->get('days_to_expiration');
            $credit->observation = $request->get('observation');
            $credit->save();

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ( (int)$dias_to_expire < 4 && (int)$dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire == 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }

                if ( $dias_to_expire > 4 )
                {
                    $credit->state_credit = 'outstanding';
                    $credit->save();
                }
            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }


            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Crédito modificado correctamente'], 200);

    }

    public function paid(Request $request)
    {
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($request->get('credit_id'));
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $credit->observation_extra = $request->get('observation2');
                $credit->date_paid = Carbon::createFromFormat('d/m/Y', $request->get('date_paid'), 'America/Lima' );
                $credit->state_credit = 'paid_out';

                if (!$request->file('image_paid')) {
                    $credit->image_paid = 'no_image.png';
                    $credit->save();
                } else {
                    $path = public_path().'/images/credits/';
                    $image = $request->file('image_paid');
                    $extension = $request->file('image_paid')->getClientOriginalExtension();
                    //$filename = $entry->id . '.' . $extension;
                    if ( $extension != 'pdf' )
                    {
                        $filename = $credit->id . '.jpg';
                        $img = Image::make($image);
                        $img->orientate();
                        $img->save($path.$filename, 80, 'jpg');
                        //$request->file('image')->move($path, $filename);
                        $credit->image_paid = $filename;
                        $credit->save();
                    } else {
                        $filename = 'pdf'.$credit->id . '.' .$extension;
                        $request->file('image_paid')->move($path, $filename);
                        $credit->image_paid = $filename;
                        $credit->save();
                    }

                }
                $credit->save();

            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Crédito pagado correctamente'], 200);

    }

    public function cancelPayCredit($idCredit)
    {
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($idCredit);
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ( (int)$dias_to_expire < 4 && (int)$dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire == 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }

                if ( $dias_to_expire > 4 )
                {
                    $credit->state_credit = 'outstanding';
                    $credit->save();
                }

                if ( $credit->image_paid != 'no_image.png' || $credit->image_paid != null )
                {
                    $path = public_path().'/images/credits/'.$credit->image_paid;
                    unlink($path);
                    $credit->image_paid = null;
                    $credit->save();
                }

                $credit->date_paid = null;
                $credit->observation_extra = '';
                $credit->save();

            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Pago cancelado correctamente'], 200);

    }

    public function indexInvoicesPending()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('credit.indexInvoicesPending', compact('permissions'));

    }

    public function getInvoicesPending()
    {
        /*$orderPurchases = OrderPurchase::with(['supplier', 'deadline'])
            ->whereNotIn('payment_deadline_id', [1,2])
            ->orderby('date_order', 'DESC')
            ->get();

        $orderServices = OrderService::with(['supplier', 'deadline'])
            ->whereNotIn('payment_deadline_id', [1,2])
            ->orderby('date_order', 'DESC')
            ->get();

        $orderPurchaseFinances = OrderPurchaseFinance::with(['supplier', 'deadline'])
            ->whereNotIn('payment_deadline_id', [1,2])
            ->orderby('date_order', 'DESC')
            ->get();

        $arrayOrders = [];

        foreach ( $orderPurchases as $orderPurchase )
        {
            $entries = Entry::where('purchase_order', $orderPurchase->code)->get();

            if ( count($entries) > 0 )
            {
                foreach ( $entries as $entry )
                {
                    $hoy = Carbon::now('America/Lima');
                    $fechaVencimiento = ($orderPurchase->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderPurchase->deadline->days);
                    $diasParaVencer = $fechaVencimiento->diffInDays($hoy);
                    array_push($arrayOrders, [
                        "order" => substr(trim($orderPurchase->code), 0, 2),
                        "correlativo" => substr(trim($orderPurchase->code), 3),
                        "proveedor" => ($orderPurchase->supplier_id == null) ? 'Sin proveedor':$orderPurchase->supplier->business_name,
                        "moneda" => ($orderPurchase->currency_order == 'PEN') ? 'Soles':'Dólares',
                        "condicion" => ($orderPurchase->payment_deadline_id == null) ? 'Sin condición':$orderPurchase->deadline->description,
                        "montoDolares" => ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:0,
                        "montoSoles" => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:0,
                        "deudaActual" => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:0,
                        "factura" => $entry->invoice,
                        "fechaEmision" => $entry->date_entry->format('d/m/Y'),
                        "fechaVencimiento" => ($orderPurchase->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderPurchase->deadline->days)->format('d/m/Y'),
                        "estado" => "FALTAN ".$diasParaVencer." DÍAS PARA VENCER",
                        "estadoPago" => "PENDIENTE"
                    ]);
                }

            } else {
                array_push($arrayOrders, [
                    "order" => substr(trim($orderPurchase->code), 0, 2),
                    "correlativo" => substr(trim($orderPurchase->code), 3),
                    "proveedor" => ($orderPurchase->supplier_id == null) ? 'Sin proveedor':$orderPurchase->supplier->business_name,
                    "moneda" => ($orderPurchase->currency_order == 'PEN') ? 'Soles':'Dólares',
                    "condicion" => ($orderPurchase->payment_deadline_id == null) ? 'Sin condición':$orderPurchase->deadline->description,
                    "montoDolares" => ($orderPurchase->currency_order == 'USD') ? $orderPurchase->total:0,
                    "montoSoles" => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:0,
                    "deudaActual" => ($orderPurchase->currency_order == 'PEN') ? $orderPurchase->total:0,
                    "factura" => "PENDIENTE",
                    "fechaEmision" => "",
                    "fechaVencimiento" => "",
                    "estado" => "",
                    "estadoPago" => "PENDIENTE"
                ]);
            }

        }

        foreach ( $orderServices as $orderService )
        {
            $entries = Entry::where('purchase_order', $orderService->code)->get();

            if ( count($entries) > 0 )
            {
                foreach ( $entries as $entry )
                {
                    $hoy = Carbon::now('America/Lima');
                    $fechaVencimiento = ($orderService->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderService->deadline->days);
                    $diasParaVencer = $fechaVencimiento->diffInDays($hoy);
                    array_push($arrayOrders, [
                        "order" => substr(trim($orderService->code), 0, 2),
                        "correlativo" => substr(trim($orderService->code), 3),
                        "proveedor" => ($orderService->supplier_id == null) ? 'Sin proveedor':$orderService->supplier->business_name,
                        "moneda" => ($orderService->currency_order == 'PEN') ? 'Soles':'Dólares',
                        "condicion" => ($orderService->payment_deadline_id == null) ? 'Sin condición':$orderService->deadline->description,
                        "montoDolares" => ($orderService->currency_order == 'USD') ? $orderService->total:0,
                        "montoSoles" => ($orderService->currency_order == 'PEN') ? $orderService->total:0,
                        "deudaActual" => ($orderService->currency_order == 'PEN') ? $orderService->total:0,
                        "factura" => $entry->invoice,
                        "fechaEmision" => $entry->date_entry->format('d/m/Y'),
                        "fechaVencimiento" => ($orderService->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderService->deadline->days)->format('d/m/Y'),
                        "estado" => "FALTAN ".$diasParaVencer." DÍAS PARA VENCER",
                        "estadoPago" => "PENDIENTE"
                    ]);
                }

            } else {
                array_push($arrayOrders, [
                    "order" => substr(trim($orderService->code), 0, 2),
                    "correlativo" => substr(trim($orderService->code), 3),
                    "proveedor" => ($orderService->supplier_id == null) ? 'Sin proveedor':$orderService->supplier->business_name,
                    "moneda" => ($orderService->currency_order == 'PEN') ? 'Soles':'Dólares',
                    "condicion" => ($orderService->payment_deadline_id == null) ? 'Sin condición':$orderService->deadline->description,
                    "montoDolares" => ($orderService->currency_order == 'USD') ? $orderService->total:0,
                    "montoSoles" => ($orderService->currency_order == 'PEN') ? $orderService->total:0,
                    "deudaActual" => ($orderService->currency_order == 'PEN') ? $orderService->total:0,
                    "factura" => "PENDIENTE",
                    "fechaEmision" => "",
                    "fechaVencimiento" => "",
                    "estado" => "",
                    "estadoPago" => "PENDIENTE"
                ]);
            }

        }

        foreach ( $orderPurchaseFinances as $orderPurchaseFinance )
        {
            $entries = Entry::where('purchase_order', $orderPurchaseFinance->code)->get();

            if ( count($entries) > 0 )
            {
                foreach ( $entries as $entry )
                {
                    $hoy = Carbon::now('America/Lima');
                    $fechaVencimiento = ($orderPurchaseFinance->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderPurchaseFinance->deadline->days);
                    $diasParaVencer = $fechaVencimiento->diffInDays($hoy);
                    array_push($arrayOrders, [
                        "order" => substr(trim($orderPurchaseFinance->code), 0, 2),
                        "correlativo" => substr(trim($orderPurchaseFinance->code), 3),
                        "proveedor" => ($orderPurchaseFinance->supplier_id == null) ? 'Sin proveedor':$orderPurchaseFinance->supplier->business_name,
                        "moneda" => ($orderPurchaseFinance->currency_order == 'PEN') ? 'Soles':'Dólares',
                        "condicion" => ($orderPurchaseFinance->payment_deadline_id == null) ? 'Sin condición':$orderPurchaseFinance->deadline->description,
                        "montoDolares" => ($orderPurchaseFinance->currency_order == 'USD') ? $orderPurchaseFinance->total:0,
                        "montoSoles" => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:0,
                        "deudaActual" => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:0,
                        "factura" => $entry->invoice,
                        "fechaEmision" => $entry->date_entry->format('d/m/Y'),
                        "fechaVencimiento" => ($orderPurchaseFinance->payment_deadline_id == null) ? $entry->date_entry: $entry->date_entry->addDays($orderPurchaseFinance->deadline->days)->format('d/m/Y'),
                        "estado" => "FALTAN ".$diasParaVencer." DÍAS PARA VENCER",
                        "estadoPago" => "PENDIENTE"
                    ]);
                }

            } else {
                array_push($arrayOrders, [
                    "order" => substr(trim($orderPurchaseFinance->code), 0, 2),
                    "correlativo" => substr(trim($orderPurchaseFinance->code), 3),
                    "proveedor" => ($orderPurchaseFinance->supplier_id == null) ? 'Sin proveedor':$orderPurchaseFinance->supplier->business_name,
                    "moneda" => ($orderPurchaseFinance->currency_order == 'PEN') ? 'Soles':'Dólares',
                    "condicion" => ($orderPurchaseFinance->payment_deadline_id == null) ? 'Sin condición':$orderPurchaseFinance->deadline->description,
                    "montoDolares" => ($orderPurchaseFinance->currency_order == 'USD') ? $orderPurchaseFinance->total:0,
                    "montoSoles" => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:0,
                    "deudaActual" => ($orderPurchaseFinance->currency_order == 'PEN') ? $orderPurchaseFinance->total:0,
                    "factura" => "PENDIENTE",
                    "fechaEmision" => "",
                    "fechaVencimiento" => "",
                    "estado" => "",
                    "estadoPago" => "PENDIENTE"
                ]);
            }

        }

        return datatables($arrayOrders)->toJson();*/
        $credits = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach( $credits as $credit )
        {
            if ( isset($credit->date_expiration) && $credit->state_credit != 'paid_out' )
            {
                $ahora = Carbon::now('America/Lima');
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays($ahora);
                if ($fecha->timestamp < $ahora->timestamp) {
                    $dias_to_expire *= -1; // Aplica el signo negativo si la primera fecha es anterior a la segunda
                }
                $credit->days_to_expiration = $dias_to_expire;
                $credit->save();

                if ( $dias_to_expire < 4 && $dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire <= 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }
            }

        }

        $credits = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        $arrayOrders = [];

        foreach ( $credits as $credit )
        {
            $pagos = CreditPay::where('supplier_credit_id', $credit->id)->sum('amount');

            array_push($arrayOrders, [
                "id" => $credit->id,
                "stateCredit" => $credit->state_credit,
                "order" => substr(trim($credit->code_order), 0, 2),
                "correlativo" => trim($credit->code_order),
                "proveedor" => ($credit->supplier_id == null) ? 'Sin proveedor':$credit->supplier->business_name,
                "moneda" => ($credit->total_soles != null) ? 'Soles':'Dólares',
                "condicion" => ($credit->payment_deadline_id == null) ? 'Sin condición':$credit->deadline->description,
                "montoDolares" => ($credit->total_dollars == null) ? '':$credit->total_dollars,
                "montoSoles" => ($credit->total_soles == null) ? '':$credit->total_soles,
                "adelanto" =>  $pagos,
                "deudaActualDolares" => ($credit->total_dollars != null) ? $credit->total_dollars-$pagos:'',
                "deudaActualSoles" => ($credit->total_soles != null) ? $credit->total_soles-$pagos:'',
                "deudaActual" => ($credit->total_soles != null) ? $credit->total_soles-$pagos:$credit->total_dollars-$pagos,
                "factura" => ($credit->invoice == null) ? 'PENDIENTE':$credit->invoice,
                "fechaEmision" => ($credit->date_issue == null) ? '': $credit->date_issue->format('d/m/Y'),
                "fechaVencimiento" => ($credit->date_expiration == null) ? '': $credit->date_expiration->format('d/m/Y'),
                "estado" => ($credit->days_to_expiration == null) ? "": $credit->days_to_expiration." DÍAS",
                "estadoPago" => $credit->state_pay,
                "fechaPago" => ($credit->date_paid == null) ? '': $credit->date_paid->format('d/m/Y'),
                "observaciones" => ($credit->observation == null) ? "": $credit->observation
            ]);
        }


        return datatables($arrayOrders)->toJson();
    }

    public function getPaysCredit( $credit_id )
    {
        $pays = CreditPay::where('supplier_credit_id', $credit_id)
            ->orderBy('date_pay', 'DESC')
            ->get();

        $arrayPays = [];
        foreach ( $pays as $pay )
        {
            array_push($arrayPays, [
                "id" => $pay->id,
                "type" => ($pay->image == null) ? 'img':substr($pay->image,  -3),
                "monto" => $pay->amount,
                "fecha" => ($pay->date_pay == null) ? '':$pay->date_pay->format('d/m/Y'),
                "comprobante" => ($pay->image == null) ? 'no_image.png':$pay->image
            ]);
        }

        return response()->json([
            "pays" => $arrayPays
        ]);
    }

    public function savePaysCredit(Request $request, $credit_id)
    {
        DB::beginTransaction();
        try {
            $credit = SupplierCredit::find($credit_id);

            $creditPay = CreditPay::create([
                'supplier_credit_id' => $credit->id,
                'amount' => $request->get('montoPago'),
                'date_pay' => ($request->get('fechaPago') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('fechaPago')) : null,
            ]);

            if ($request->hasFile('comprobantePago')) {
                $image = $request->file('comprobantePago');
                $path = public_path().'/images/credits/pays/';
                $extension = $request->file('comprobantePago')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $creditPay->id.'_' . $this->generateRandomString(20).'.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $creditPay->image = $filename;
                    $creditPay->save();
                } else {
                    $filename = 'pdf'.$creditPay->id . $this->generateRandomString(20) . '.' .$extension;
                    $request->file('comprobantePago')->move($path, $filename);
                    $creditPay->image = $filename;
                    $creditPay->save();
                }
            }


            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Pago registrado con exito.',
            'credit' => $credit],
            200);

    }

    public function deletePayCredit(Request $request, $credit_pay_id)
    {
        DB::beginTransaction();
        try {

            $creditPay = CreditPay::find($credit_pay_id);

            $credit = SupplierCredit::find($creditPay->supplier_credit_id);

            $creditPay->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Pago eliminado con exito.',
            'credit' => $credit],
            200);

    }

    public function addDaysCredit(Request $request, $credit_id)
    {
        $credit = SupplierCredit::find($credit_id);

        if ( !isset($credit->date_expiration) || $credit->state_credit == 'paid_out' ) {
            return response()->json([
                'message' => "No se puede agregar dias porque la fecha de expiración no existe o el estado del crédito ya esta pagado"],
                422);
        }

        DB::beginTransaction();
        try {
            $newDate = $credit->date_expiration->addDays(7);

            $credit->date_expiration = $newDate;

            $credit->save();

            $ahora = Carbon::now('America/Lima');
            $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
            $dias_to_expire = $fecha->diffInDays($ahora);
            if ($fecha->timestamp < $ahora->timestamp) {
                $dias_to_expire *= -1; // Aplica el signo negativo si la primera fecha es anterior a la segunda
            }
            $credit->days_to_expiration = $dias_to_expire;
            $credit->save();

            if ( $dias_to_expire >= 4 )
            {
                $credit->state_credit = 'outstanding';
                $credit->save();
            }

            if ( $dias_to_expire < 4 && $dias_to_expire > 0 )
            {
                $credit->state_credit = 'by_expire';
                $credit->save();
            }

            if ( $dias_to_expire <= 0 )
            {
                $credit->state_credit = 'expired';
                $credit->save();
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Se agregaron 7 días más a la fecha de expiración.',
            'credit' => $credit],
            200);
    }

    public function getSummaryDeudaPending()
    {
        $credits = SupplierCredit::where('state_pay', '<>', 'canceled')->get();

        $deudaSoles = 0;
        $deudaDolares = 0;
        foreach ( $credits as $credit )
        {
            if ( $credit->total_soles != null )
            {
                $deudaSoles = $deudaSoles + ($credit->total_soles-$credit->advance );
            }

            if ( $credit->total_dollars != null )
            {
                $deudaDolares = $deudaDolares + ( $credit->total_dollars-$credit->advance );
            }

        }

        return response()->json([
            "deudaSoles" => $deudaSoles,
            "deudaDolares" => $deudaDolares
        ], 200);
    }

    public function generateRandomString($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
