<?php

namespace App\Http\Controllers;

use App\Audit;
use App\DateDimension;
use App\Entry;
use App\Exports\ExpenseSuppliersExport;
use App\OrderPurchase;
use App\OrderService;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseSupplierController extends Controller
{
    public function getDataExpenseSuppliers(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $number_order = $request->input('number_order');
        $year = $request->input('year');
        $supplier = $request->input('supplier');
        $date_due = $request->input('date_due');
        $stateCredit = $request->input('stateCredit');
        $statePaid = $request->input('statePaid');
        $month_order = $request->input('month_order');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $credito = $request->input('credito');

        if ( $startDate == "" || $endDate == "" )
        {
            $dateCurrent = Carbon::now('America/Lima');
            //$date4MonthAgo = $dateCurrent->subMonths(6);
            /*$query = OrderPurchase::with(['supplier', 'entries'])
                ->select('id', 'date_order','code', DB::raw("'op' as type"))
                ->union(
                    OrderService::with('supplier')
                        ->select('id', 'date_order', 'code', DB::raw("'os' as type"))
                )->orderBy('date_order', 'desc');*/
            $queryPurchase = OrderPurchase::with(['supplier', 'entries', 'deadline'])
                ->select('order_purchases.id', 'date_order','code', DB::raw("'op' as type"));

            // Subconsulta para OrderService
            $queryService = OrderService::with(['supplier', 'deadline'])
                ->select('order_services.id', 'date_order', 'code', DB::raw("'os' as type"));
        } else {
            $fechaInicio = Carbon::createFromFormat('d/m/Y', $startDate);
            $fechaFinal = Carbon::createFromFormat('d/m/Y', $endDate);

            /*$query = OrderPurchase::with(['supplier', 'entries'])
                ->whereDate('date_order', '>=', $fechaInicio)
                ->whereDate('date_order', '<=', $fechaFinal)
                ->select('id', 'date_order','code', DB::raw("'op' as type"))
                ->union(
                    OrderService::with('supplier')
                        ->whereDate('date_order', '>=', $fechaInicio)
                        ->whereDate('date_order', '<=', $fechaFinal)
                        ->select('id', 'date_order','code', DB::raw("'os' as type"))
                )->orderBy('date_order', 'desc');*/
            $queryPurchase = OrderPurchase::with(['supplier', 'entries', 'deadline'])
                ->whereDate('date_order', '>=', $fechaInicio)
                ->whereDate('date_order', '<=', $fechaFinal)
                ->select('order_purchases.id', 'date_order','code', DB::raw("'op' as type"));

            // Subconsulta para OrderService
            $queryService = OrderService::with(['supplier', 'deadline'])
                ->whereDate('date_order', '>=', $fechaInicio)
                ->whereDate('date_order', '<=', $fechaFinal)
                ->select('order_services.id', 'date_order', 'code', DB::raw("'os' as type"));
        }

        //dump($query->get('code'));



        // Aplicar filtros si se proporcionan
        if ($number_order != "") {
            /*$query->where('code', $number_order);*/
            $queryPurchase->where('code', 'LIKE', '%'.$number_order.'%');
            $queryService->where('code', 'LIKE', '%'.$number_order.'%');
        }

        if ($supplier != "") {
            /*$query->whereHas('supplier', function ($query2) use ($supplier) {
                $query2->where('supplier_id', $supplier);
            });*/
            $queryPurchase->whereHas('supplier', function ($supplierQuery) use ($supplier) {
                $supplierQuery->where('supplier_id', $supplier);
            });
            $queryService->whereHas('supplier', function ($supplierQuery) use ($supplier) {
                $supplierQuery->where('supplier_id', $supplier);
            });

        }

        if ($year != "") {
            /*$query->whereYear('date_order', $year);*/
            $queryPurchase->whereYear('date_order', $year);
            $queryService->whereYear('date_order', $year);

        }

        if ($month_order != "") {
            /*$query->whereMonth('date_order', $month_order);*/
            $queryPurchase->whereMonth('date_order', $month_order);
            $queryService->whereMonth('date_order', $month_order);
        }

        if ($statePaid != "")
        {
            /*$query->where(function ($subquery) use ($statePaid) {
                // Filtrar por estado pagado en Entries
                $subquery->whereHas('entries', function ($entriesSubquery) use ($statePaid) {
                    $entriesSubquery->where('state_paid', $statePaid);
                });

                // También filtrar por estado pagado en OrderService
                $subquery->orWhere(function ($orderServiceSubquery) use ($statePaid) {
                    $orderServiceSubquery->where('state_paid', $statePaid);
                });
            });*/
            $queryPurchase->whereHas('entries', function ($entriesSubquery) use ($statePaid) {
                $entriesSubquery->where('state_paid', $statePaid);
            });
            $queryService->where('state_paid', $statePaid);
        }

        if ($date_due != "") {
            $date_due = Carbon::createFromFormat('d/m/Y', $date_due)->format('Y-m-d');

            $queryPurchase->whereHas('entries', function ($entriesSubquery) use ($date_due) {
                $entriesSubquery->where(
                    DB::raw('DATE_ADD(entries.date_entry, INTERVAL payment_deadlines.days DAY)'),
                    '<=',
                    $date_due
                );
            });
            $queryPurchase->leftJoin('payment_deadlines', 'order_purchases.payment_deadline_id', '=', 'payment_deadlines.id')
                ->addSelect('payment_deadlines.days as deadline_days');

            $queryService->where(
                DB::raw('DATE_ADD(order_services.date_invoice, INTERVAL payment_deadlines.days DAY)'),
                '<=',
                $date_due
            );

            $queryService->leftJoin('payment_deadlines', 'order_services.payment_deadline_id', '=', 'payment_deadlines.id')
                ->addSelect('payment_deadlines.days as deadline_days');

        }

        if ($stateCredit != "") {
            $now = Carbon::now()->toDateString(); // Obtener la fecha actual

            if ($stateCredit == 1) {
                // VENCE HOY
                $queryPurchase->whereHas('entries', function ($entriesSubquery) use ($now) {
                    $entriesSubquery->whereDate(
                        DB::raw('DATE_ADD(entries.date_entry, INTERVAL payment_deadlines.days DAY)'),
                        $now
                    );
                });

                $queryPurchase->leftJoin('payment_deadlines', 'order_purchases.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

                $queryService->whereDate(
                    DB::raw('DATE_ADD(order_services.date_invoice, INTERVAL payment_deadlines.days DAY)'),
                    $now
                );

                $queryService->leftJoin('payment_deadlines', 'order_services.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

            } elseif ($stateCredit == 2) {
                // POR VENCER
                $queryPurchase->whereHas('entries', function ($entriesSubquery) use ($now) {
                    $entriesSubquery->whereDate(
                        DB::raw('DATE_ADD(entries.date_entry, INTERVAL payment_deadlines.days DAY)'),
                        '>',
                        $now
                    );
                });

                $queryPurchase->leftJoin('payment_deadlines', 'order_purchases.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

                $queryService->whereDate(
                    DB::raw('DATE_ADD(order_services.date_invoice, INTERVAL payment_deadlines.days DAY)'),
                    '>',
                    $now
                );

                $queryService->leftJoin('payment_deadlines', 'order_services.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

            } elseif ($stateCredit == 3) {
                // VENCIDO
                $queryPurchase->whereHas('entries', function ($entriesSubquery) use ($now) {
                    $entriesSubquery->whereDate(
                        DB::raw('DATE_ADD(entries.date_entry, INTERVAL payment_deadlines.days DAY)'),
                        '<',
                        $now
                    );
                });

                $queryPurchase->leftJoin('payment_deadlines', 'order_purchases.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

                $queryService->whereDate(
                    DB::raw('DATE_ADD(order_services.date_invoice, INTERVAL payment_deadlines.days DAY)'),
                    '<',
                    $now
                );
                $queryService->leftJoin('payment_deadlines', 'order_services.payment_deadline_id', '=', 'payment_deadlines.id')
                    ->addSelect('payment_deadlines.days as deadline_days');

            }
        }

        if ($credito != "") {
            $queryPurchase->whereHas('deadline', function ($supplierQuery) use ($credito) {
                if ($credito == 1) {
                    $supplierQuery->whereIn('payment_deadline_id', [1, 2])->where('type', 'purchases');
                } elseif ($credito == 2) {
                    $supplierQuery->whereNotIn('payment_deadline_id', [1, 2])->where('type', 'purchases');
                }
            });

            $queryService->whereHas('deadline', function ($supplierQuery) use ($credito) {
                if ($credito == 1) {
                    $supplierQuery->whereIn('payment_deadline_id', [1, 2])->where('type', 'purchases');
                } elseif ($credito == 2) {
                    $supplierQuery->whereNotIn('payment_deadline_id', [1, 2])->where('type', 'purchases');
                }
            });
        }

        /*dump($queryPurchase->get());
        dump($queryService->get());
        dd();*/

        $query = $queryPurchase->union($queryService)->orderBy('date_order', 'desc');


        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $expense_suppliers = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        //dd($query);

        $array = [];

        $date_current = Carbon::now('America/Lima');

        foreach ( $expense_suppliers as $expense )
        {
            if ( $expense->type == 'op' )
            {
                // TODO: OrderPurchase
                $order = OrderPurchase::with('supplier', 'deadline')->find($expense->id);

                $invoices = Entry::where('purchase_order', $order->code)->get();

                $invoice = "";
                $date_invoice = "";

                if ( count($invoices) == 0 )
                {
                    $invoice = "SIN FACTURA";
                    $date_invoice = "SIN FECHA";
                    $date_due = "SIN FECHA";
                    array_push($array, [
                        "id" => "",
                        "type" => "oc",
                        "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                        "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                        "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                        "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                        "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                        "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                        "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                        "invoice" => $invoice,
                        "date_invoice" => $date_invoice,
                        "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                        "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                        "due_date" => $date_due,
                        "state_credit" => "",
                        "state_paid" => "",
                    ]);
                } elseif ( count($invoices) == 1 )
                {
                    $invoice = $invoices[0]->invoice;
                    $date_invoice = $invoices[0]->date_entry->format('d/m/Y');
                    $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                    $date_due = $invoices[0]->date_entry->addDays($days);
                    if ($date_due->isSameDay($date_current)) {
                        $state_credit = "VENCE HOY";
                    } elseif ($date_due->isAfter($date_current)) {
                        $state_credit = "POR VENCER";
                    } else {
                        $state_credit = '<p style="color: red;font-weight: bold">VENCIDO</p>';
                    }

                    if ($invoices[0]->state_paid == null)
                    {
                        $state_paid = "";
                    } elseif($invoices[0]->state_paid == "pending") {
                        $state_paid = "PENDIENTE DE ABONAR";
                    } else {
                        $state_paid = "ABONADO";
                    }
                    array_push($array, [
                        "id" => $invoices[0]->id,
                        "type" => "oc",
                        "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                        "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                        "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                        "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                        "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                        "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                        "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                        "invoice" => $invoice,
                        "date_invoice" => $date_invoice,
                        "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                        "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                        "due_date" => $date_due->format('d/m/Y'),
                        "state_credit" => $state_credit,
                        "state_paid" => $state_paid
                    ]);
                } elseif ( count($invoices) > 1 )
                {
                    foreach ( $invoices as $i )
                    {
                        $invoice = $i->invoice;
                        $date_invoice = $i->date_entry->format('d/m/Y');
                        $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                        $date_due = $i->date_entry->addDays($days);

                        if ($date_due->isSameDay($date_current)) {
                            $state_credit = "VENCE HOY";
                        } elseif ($date_due->isAfter($date_current)) {
                            $state_credit = "POR VENCER";
                        } else {
                            $state_credit = '<p style="color: red;font-weight: bold">VENCIDO</p>';
                        }

                        if ($i->state_paid == null)
                        {
                            $state_paid = "";
                        } elseif($i->state_paid == "pending") {
                            $state_paid = "PENDIENTE DE ABONAR";
                        } else {
                            $state_paid = "ABONADO";
                        }
                        array_push($array, [
                            "id" => $i->id,
                            "type" => "oc",
                            "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                            "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                            "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                            "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                            "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                            "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                            "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                            "invoice" => $invoice,
                            "date_invoice" => $date_invoice,
                            "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                            "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                            "due_date" => $date_due->format('d/m/Y'),
                            "state_credit" => $state_credit,
                            "state_paid" => $state_paid
                        ]);
                    }
                }

            } else {
                // TODO: OrderService
                $order = OrderService::with('supplier', 'deadline')->find($expense->id);
                $invoice = $order->invoice;
                $date_invoice = ($order->date_invoice == null) ? 'SIN FECHA': $order->date_invoice->format('d/m/Y');
                $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                $date_due = ($order->date_invoice == null) ? 'SIN FECHA':$order->date_invoice->addDays($days);

                if ( $date_due == "SIN FECHA" )
                {
                    $state_credit = "";
                } else {
                    if ($date_due->isSameDay($date_current)) {
                        $state_credit = "VENCE HOY";
                    } elseif ($date_due->isAfter($date_current)) {
                        $state_credit = "POR VENCER";
                    } else {
                        $state_credit = '<p style="color: red;font-weight: bold">VENCIDO</p>';
                    }
                }

                if ($order->state_paid == null)
                {
                    $state_paid = "";
                } elseif($order->state_paid == "pending") {
                    $state_paid = "PENDIENTE DE ABONAR";
                } else {
                    $state_paid = "ABONADO";
                }

                array_push($array, [
                    "id" => $order->id,
                    "type" => "os",
                    "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                    "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                    "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                    "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                    "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                    "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                    "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                    "invoice" => $invoice,
                    "date_invoice" => $date_invoice,
                    "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                    "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                    "due_date" => ($date_due == "SIN FECHA") ? "SIN FECHA": $date_due->format('d/m/Y'),
                    "state_credit" => $state_credit,
                    "state_paid" => $state_paid
                ]);
            }


        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function indexV2()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        // Obtener los registros de OrderPurchase
        $purchaseRecords = OrderPurchase::all();

        // Obtener los registros de OrderService
        $serviceRecords = OrderService::all();

        // Unir ambos conjuntos de registros
        $allRecords = $purchaseRecords->merge($serviceRecords);

        // Obtener los años de las fechas en ambos modelos
        $arrayYears = $allRecords->pluck('date_order')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $arrayYears = array_values($arrayYears);

        $years = DateDimension::distinct()->get(['year']);

        $arraySuppliers = Supplier::select('id', 'business_name')->get()->toArray();

        $arrayStateCredits = [
            ["value" => "1", "display" => "VENCE HOY"],
            ["value" => "2", "display" => "POR VENCER"],
            ["value" => "3", "display" => "VENCIDO"]
        ];

        $arrayStatePaids = [
            ["value" => "pending", "display" => "PENDIENTE DE ABONO"],
            ["value" => "paid", "display" => "ABONADO"],
        ];

        $arrayStateDeadlines = [
            ["value" => "1", "display" => "AL CONTADO"],
            ["value" => "2", "display" => "AL CRÉDITO"],
        ];

        //$tiposCambios = $this->getTypeExchange();
        //dump($tiposCambios);
        //$firstDayWeek = Carbon::now('America/Lima')->format('Y-m-d');
        //dump($firstDayWeek);
        //$tipoCambio = $this->getExchange($firstDayWeek, $tiposCambios);
        //$rate = $tipoCambio->compra;
        $day_current = Carbon::now('America/Lima');

        return view('expenseSupplier.index_v2', compact( 'arrayStateDeadlines', 'years', 'day_current', 'arrayYears', 'permissions', 'arraySuppliers', 'arrayStateCredits', 'arrayStatePaids'));

    }

    public function obtenerNombreMes($numeroMes) {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return isset($meses[$numeroMes]) ? $meses[$numeroMes] : 'Mes inválido';
    }

    public function exportExpenseSuppliers()
    {
        $begin = microtime(true);
        //dd($request);
        $start = $_GET['start'];
        $end = $_GET['end'];
        //dump($start);
        //dump($end);
        $array = [];
        $dates = '';

        if ( $start == '' || $end == '' )
        {
            //dump('Descargar todos');
            $dates = 'EGRESOS PROVEEDORES';
            $queryPurchase = OrderPurchase::with(['supplier', 'entries', 'deadline'])
                ->select('order_purchases.id', 'date_order','code', DB::raw("'op' as type"));

            // Subconsulta para OrderService
            $queryService = OrderService::with(['supplier', 'deadline'])
                ->select('order_services.id', 'date_order', 'code', DB::raw("'os' as type"));

            $query = $queryPurchase->union($queryService)->orderBy('date_order', 'desc');

            $expense_suppliers = $query->get();

            $date_current = Carbon::now('America/Lima');

            foreach ( $expense_suppliers as $expense )
            {
                if ( $expense->type == 'op' )
                {
                    // TODO: OrderPurchase
                    $order = OrderPurchase::with('supplier', 'deadline')->find($expense->id);

                    $invoices = Entry::where('purchase_order', $order->code)->get();

                    $invoice = "";
                    $date_invoice = "";

                    if ( count($invoices) == 0 )
                    {
                        $invoice = "SIN FACTURA";
                        $date_invoice = "SIN FECHA";
                        $date_due = "SIN FECHA";
                        array_push($array, [
                            "id" => "",
                            "type" => "oc",
                            "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                            "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                            "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                            "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                            "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                            "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                            "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                            "invoice" => $invoice,
                            "date_invoice" => $date_invoice,
                            "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                            "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                            "due_date" => $date_due,
                            "state_credit" => "",
                            "state_paid" => "",
                        ]);
                    } elseif ( count($invoices) == 1 )
                    {
                        $invoice = $invoices[0]->invoice;
                        $date_invoice = $invoices[0]->date_entry->format('d/m/Y');
                        $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                        $date_due = $invoices[0]->date_entry->addDays($days);
                        if ($date_due->isSameDay($date_current)) {
                            $state_credit = "VENCE HOY";
                        } elseif ($date_due->isAfter($date_current)) {
                            $state_credit = "POR VENCER";
                        } else {
                            $state_credit = 'VENCIDO';
                        }

                        if ($invoices[0]->state_paid == null)
                        {
                            $state_paid = "";
                        } elseif($invoices[0]->state_paid == "pending") {
                            $state_paid = "PENDIENTE DE ABONAR";
                        } else {
                            $state_paid = "ABONADO";
                        }
                        array_push($array, [
                            "id" => $invoices[0]->id,
                            "type" => "oc",
                            "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                            "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                            "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                            "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                            "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                            "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                            "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                            "invoice" => $invoice,
                            "date_invoice" => $date_invoice,
                            "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                            "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                            "due_date" => $date_due->format('d/m/Y'),
                            "state_credit" => $state_credit,
                            "state_paid" => $state_paid
                        ]);
                    } elseif ( count($invoices) > 1 )
                    {
                        foreach ( $invoices as $i )
                        {
                            $invoice = $i->invoice;
                            $date_invoice = $i->date_entry->format('d/m/Y');
                            $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                            $date_due = $i->date_entry->addDays($days);

                            if ($date_due->isSameDay($date_current)) {
                                $state_credit = "VENCE HOY";
                            } elseif ($date_due->isAfter($date_current)) {
                                $state_credit = "POR VENCER";
                            } else {
                                $state_credit = 'VENCIDO';
                            }

                            if ($i->state_paid == null)
                            {
                                $state_paid = "";
                            } elseif($i->state_paid == "pending") {
                                $state_paid = "PENDIENTE DE ABONAR";
                            } else {
                                $state_paid = "ABONADO";
                            }
                            array_push($array, [
                                "id" => $i->id,
                                "type" => "oc",
                                "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                                "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                                "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                                "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                                "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                                "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                                "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                                "invoice" => $invoice,
                                "date_invoice" => $date_invoice,
                                "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                                "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                                "due_date" => $date_due->format('d/m/Y'),
                                "state_credit" => $state_credit,
                                "state_paid" => $state_paid
                            ]);
                        }
                    }

                } else {
                    // TODO: OrderService
                    $order = OrderService::with('supplier', 'deadline')->find($expense->id);
                    $invoice = $order->invoice;
                    $date_invoice = ($order->date_invoice == null) ? 'SIN FECHA': $order->date_invoice->format('d/m/Y');
                    $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                    $date_due = ($order->date_invoice == null) ? 'SIN FECHA':$order->date_invoice->addDays($days);

                    if ( $date_due == "SIN FECHA" )
                    {
                        $state_credit = "";
                    } else {
                        if ($date_due->isSameDay($date_current)) {
                            $state_credit = "VENCE HOY";
                        } elseif ($date_due->isAfter($date_current)) {
                            $state_credit = "POR VENCER";
                        } else {
                            $state_credit = 'VENCIDO';
                        }
                    }

                    if ($order->state_paid == null)
                    {
                        $state_paid = "";
                    } elseif($order->state_paid == "pending") {
                        $state_paid = "PENDIENTE DE ABONAR";
                    } else {
                        $state_paid = "ABONADO";
                    }

                    array_push($array, [
                        "id" => $order->id,
                        "type" => "os",
                        "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                        "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                        "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                        "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                        "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                        "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                        "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                        "invoice" => $invoice,
                        "date_invoice" => $date_invoice,
                        "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                        "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                        "due_date" => ($date_due == "SIN FECHA") ? "SIN FECHA": $date_due->format('d/m/Y'),
                        "state_credit" => $state_credit,
                        "state_paid" => $state_paid
                    ]);
                }


            }


        } else {
            $date_start = Carbon::createFromFormat('d/m/Y', $start);
            $end_start = Carbon::createFromFormat('d/m/Y', $end);

            $dates = 'INGRESOS CLIENTES DEL '. $start .' AL '. $end;

            $queryPurchase = OrderPurchase::with(['supplier', 'entries', 'deadline'])
                ->whereDate('date_order', '>=', $date_start)
                ->whereDate('date_order', '<=', $end_start)
                ->select('order_purchases.id', 'date_order','code', DB::raw("'op' as type"));

            // Subconsulta para OrderService
            $queryService = OrderService::with(['supplier', 'deadline'])
                ->whereDate('date_order', '>=', $date_start)
                ->whereDate('date_order', '<=', $end_start)
                ->select('order_services.id', 'date_order', 'code', DB::raw("'os' as type"));

            $query = $queryPurchase->union($queryService)->orderBy('date_order', 'desc');

            $expense_suppliers = $query->get();

            $date_current = Carbon::now('America/Lima');

            foreach ( $expense_suppliers as $expense )
            {
                if ( $expense->type == 'op' )
                {
                    // TODO: OrderPurchase
                    $order = OrderPurchase::with('supplier', 'deadline')->find($expense->id);

                    $invoices = Entry::where('purchase_order', $order->code)->get();

                    $invoice = "";
                    $date_invoice = "";

                    if ( count($invoices) == 0 )
                    {
                        $invoice = "SIN FACTURA";
                        $date_invoice = "SIN FECHA";
                        $date_due = "SIN FECHA";
                        array_push($array, [
                            "id" => "",
                            "type" => "oc",
                            "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                            "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                            "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                            "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                            "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                            "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                            "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                            "invoice" => $invoice,
                            "date_invoice" => $date_invoice,
                            "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                            "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                            "due_date" => $date_due,
                            "state_credit" => "",
                            "state_paid" => "",
                        ]);
                    } elseif ( count($invoices) == 1 )
                    {
                        $invoice = $invoices[0]->invoice;
                        $date_invoice = $invoices[0]->date_entry->format('d/m/Y');
                        $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                        $date_due = $invoices[0]->date_entry->addDays($days);
                        if ($date_due->isSameDay($date_current)) {
                            $state_credit = "VENCE HOY";
                        } elseif ($date_due->isAfter($date_current)) {
                            $state_credit = "POR VENCER";
                        } else {
                            $state_credit = 'VENCIDO';
                        }

                        if ($invoices[0]->state_paid == null)
                        {
                            $state_paid = "";
                        } elseif($invoices[0]->state_paid == "pending") {
                            $state_paid = "PENDIENTE DE ABONAR";
                        } else {
                            $state_paid = "ABONADO";
                        }
                        array_push($array, [
                            "id" => $invoices[0]->id,
                            "type" => "oc",
                            "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                            "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                            "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                            "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                            "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                            "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                            "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                            "invoice" => $invoice,
                            "date_invoice" => $date_invoice,
                            "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                            "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                            "due_date" => $date_due->format('d/m/Y'),
                            "state_credit" => $state_credit,
                            "state_paid" => $state_paid
                        ]);
                    } elseif ( count($invoices) > 1 )
                    {
                        foreach ( $invoices as $i )
                        {
                            $invoice = $i->invoice;
                            $date_invoice = $i->date_entry->format('d/m/Y');
                            $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                            $date_due = $i->date_entry->addDays($days);

                            if ($date_due->isSameDay($date_current)) {
                                $state_credit = "VENCE HOY";
                            } elseif ($date_due->isAfter($date_current)) {
                                $state_credit = "POR VENCER";
                            } else {
                                $state_credit = 'VENCIDO';
                            }

                            if ($i->state_paid == null)
                            {
                                $state_paid = "";
                            } elseif($i->state_paid == "pending") {
                                $state_paid = "PENDIENTE DE ABONAR";
                            } else {
                                $state_paid = "ABONADO";
                            }
                            array_push($array, [
                                "id" => $i->id,
                                "type" => "oc",
                                "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                                "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                                "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                                "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                                "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                                "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                                "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                                "invoice" => $invoice,
                                "date_invoice" => $date_invoice,
                                "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                                "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                                "due_date" => $date_due->format('d/m/Y'),
                                "state_credit" => $state_credit,
                                "state_paid" => $state_paid
                            ]);
                        }
                    }

                } else {
                    // TODO: OrderService
                    $order = OrderService::with('supplier', 'deadline')->find($expense->id);
                    $invoice = $order->invoice;
                    $date_invoice = ($order->date_invoice == null) ? 'SIN FECHA': $order->date_invoice->format('d/m/Y');
                    $days = ($order->payment_deadline_id != null) ? $order->deadline->days : 0;
                    $date_due = ($order->date_invoice == null) ? 'SIN FECHA':$order->date_invoice->addDays($days);

                    if ( $date_due == "SIN FECHA" )
                    {
                        $state_credit = "";
                    } else {
                        if ($date_due->isSameDay($date_current)) {
                            $state_credit = "VENCE HOY";
                        } elseif ($date_due->isAfter($date_current)) {
                            $state_credit = "POR VENCER";
                        } else {
                            $state_credit = 'VENCIDO';
                        }
                    }

                    if ($order->state_paid == null)
                    {
                        $state_paid = "";
                    } elseif($order->state_paid == "pending") {
                        $state_paid = "PENDIENTE DE ABONAR";
                    } else {
                        $state_paid = "ABONADO";
                    }

                    array_push($array, [
                        "id" => $order->id,
                        "type" => "os",
                        "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                        "month" => ($order->date_order == null) ? '': $this->obtenerNombreMes($order->date_order->month),
                        "date_order" => ($order->date_order == null) ? '': $order->date_order->format('d/m/Y'),
                        "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                        "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                        "soles" => ($order->currency_order == 'PEN') ? $order->total : '',
                        "dolares" => ($order->currency_order == 'USD') ? $order->total : '',
                        "invoice" => $invoice,
                        "date_invoice" => $date_invoice,
                        "deadline" => ($order->payment_deadline_id != null) ? $order->deadline->description : '',
                        "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                        "due_date" => ($date_due == "SIN FECHA") ? "SIN FECHA": $date_due->format('d/m/Y'),
                        "state_credit" => $state_credit,
                        "state_paid" => $state_paid
                    ]);
                }


            }

        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Reporte Excel Egresos Proveedores',
            'time' => $end
        ]);

        return (new ExpenseSuppliersExport($array, $dates))->download('egresosProveedores.xlsx');

    }

    public function getInfoFacturacionExpenseSupplier($invoice_id, $type)
    {
        //dump($invoice_id);
        //dd();
        $state = "";
        if ( $type == "oc" )
        {
            if ( $invoice_id != "nn" )
            {
                $entry = Entry::find($invoice_id);
                $state = $entry->state_paid;
            }
        } elseif ( $type == "os" ) {
            if ( $invoice_id != "nn" )
            {
                $orderService = OrderService::find($invoice_id);
                $state = $orderService->state_paid;
            }
        }


        return response()->json([
            "state" => $state,
        ]);
    }

    public function expenseSupplierEditFacturacion( Request $request )
    {
        //dd($request);
        $type = $request->get('type');
        $invoice_id = $request->get('invoice_id');
        $state = $request->get('state');

        DB::beginTransaction();
        try {

            if ( $type == "oc" )
            {
                if ( $invoice_id == null || $invoice_id == "" )
                {
                    return response()->json(['message' => "No se encuentra una factura."], 422);
                } else {
                    $entry = Entry::find($invoice_id);
                    $entry->state_paid = $state;
                    $entry->save();
                }
            } elseif ( $type == "os" ) {
                if ( $invoice_id == null || $invoice_id == "" )
                {
                    return response()->json(['message' => "No se encuentra una factura."], 422);
                } else {
                    $entry = OrderService::find($invoice_id);

                    if ( $entry->invoice == "" || $entry->invoice == null )
                    {
                        return response()->json(['message' => "No se encuentra una factura."], 422);
                    } else {
                        $entry->state_paid = $state;
                        $entry->save();
                    }
                }
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Información de Facturación modificado con éxito.'], 200);

    }
}
