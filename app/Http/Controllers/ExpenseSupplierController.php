<?php

namespace App\Http\Controllers;

use App\Entry;
use App\OrderPurchase;
use App\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseSupplierController extends Controller
{
    public function getDataExpenseSuppliers(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        /*$description = $request->input('description');
        $year = $request->input('year');
        $code = $request->input('code');
        $order = $request->input('order');
        $customer = $request->input('customer');
        $stateWork = $request->input('stateWork');
        $year_factura = $request->input('year_factura');
        $month_factura = $request->input('month_factura');
        $year_abono = $request->input('year_abono');
        $month_abono = $request->input('month_abono');
        $state_invoice = $request->input('state_invoice');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $rate = $request->input('rate');*/

        /*if ( $startDate == "" || $endDate == "" )
        {
            $dateCurrent = Carbon::now('America/Lima');
            $date4MonthAgo = $dateCurrent->subMonths(6);
            $query = FinanceWork::with('quote', 'bank')
                ->where('created_at', '>=', $date4MonthAgo)
                ->orderBy('created_at', 'DESC');
        } else {
            $fechaInicio = Carbon::createFromFormat('d/m/Y', $startDate);
            $fechaFinal = Carbon::createFromFormat('d/m/Y', $endDate);

            $query = FinanceWork::with('quote', 'bank')
                ->whereHas('quote', function ($query2) use ($fechaInicio, $fechaFinal) {
                    $query2->whereDate('date_quote', '>=', $fechaInicio)
                        ->whereDate('date_quote', '<=', $fechaFinal);
                })
                ->orderBy('created_at', 'DESC');
        }

        // Aplicar filtros si se proporcionan
        if ($description) {
            $query->whereHas('quote', function ($query2) use ($description) {
                $query2->where('description_quote', 'LIKE', '%'.$description.'%');
            });

        }

        if ($year) {
            $query->whereYear('raise_date', $year);

        }

        if ($code) {
            $query->whereHas('quote', function ($query2) use ($code) {
                $query2->where('code', 'LIKE', '%'.$code.'%');
            });

        }

        if ($order) {
            $query->whereHas('quote', function ($query2) use ($order) {
                $query2->where('code_customer', 'LIKE', '%'.$order.'%');
            });

        }

        if ($customer) {
            $query->whereHas('quote', function ($query2) use ($customer) {
                $query2->where('customer_id', $customer);
            });

        }

        if ($stateWork) {
            $query->where('state_work', $stateWork);
        }

        if ($year_factura) {
            $query->where('year_invoice', $year_factura);
        }

        if ($month_factura) {
            $query->where('month_invoice', $month_factura);
        }

        if ($year_abono) {
            $query->where('year_paid', $year_abono);
        }

        if ($month_abono) {
            $query->where('month_paid', $month_abono);
        }

        if ($state_invoice) {
            $query->where('state', $state_invoice);
        }*/

        /*$query = OrderPurchase::with('supplier');
        $query = OrderService::with('supplier');*/

        $query = OrderPurchase::with('supplier')
            ->select('id', DB::raw("'op' as type")) // Ajusta los campos según tus necesidades
            ->union(
                OrderService::with('supplier')
                    ->select('id', DB::raw("'os' as type")) // Ajusta los campos según tus necesidades
            );

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $expense_suppliers = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        //dd($proformas);

        $array = [];

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
                } elseif ( count($invoices) == 1 )
                {
                    $invoice = $invoices[0]->invoice;
                    $date_invoice = $invoices[0]->date_entry->format('d/m/Y');
                } elseif ( count($invoices) > 1 )
                {
                    foreach ( $invoices as $i )
                    {
                        $invoice = $invoice . $i->invoice ."<br>";
                        $date_invoice = $date_invoice . $invoices[0]->date_entry->format('d/m/Y') ."<br>";
                    }
                }

                array_push($array, [
                    "id" => $order->id,
                    "year" => ($order->date_order == null) ? '': $order->date_order->format('Y'),
                    "month" => ($order->date_order == null) ? '': $order->date_order->format('m'),
                    "supplier" => ($order->supplier_id == null) ? '': $order->supplier->business_name,
                    "order" => ($order->code == null || $order->code == '') ? '': $order->code,
                    "soles" => ($order->currency == 'PEN') ? $order->total : '',
                    "dolares" => ($order->currency == 'USD') ? $order->total : '',
                    "invoice" => $invoice,
                    "date_invoice" => $date_invoice,
                    "days" => ($order->payment_deadline_id != null) ? $order->deadline->days : '',
                    "due_date" => "",
                    "state_credit" => "",
                    "state_paid" => "",
                ]);
            } else {
                // TODO: OrderService
                $order = OrderPurchase::with('supplier', 'deadline')->find($expense->id);
                array_push($array, [
                    "id" => "",
                    "year" => "",
                    "month" => "",
                    "supplier" => "",
                    "order" => "",
                    "soles" => "",
                    "dolares" => "",
                    "invoice" => "",
                    "date_invoice" => "",
                    "days" => "",
                    "due_date" => "",
                    "state_credit" => "",
                    "state_paid" => "",
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

        $registros = FinanceWork::all();

        $arrayYears = $registros->pluck('raise_date')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $arrayYears = array_values($arrayYears);

        $arrayCustomers = Customer::select('id', 'business_name')->get()->toArray();

        $arrayStateWorks = [
            ["value" => "to_start", "display" => "POR INICIAR"],
            ["value" => "in_process", "display" => "EN PROCESO"],
            ["value" => "finished", "display" => "TERMINADO"],
            ["value" => "stopped", "display" => "PAUSADO"],
            ["value" => "canceled", "display" => "CANCELADO"]
        ];

        $arrayStates = [
            ["value" => "pending", "display" => "PENDIENTE DE ABONO"],
            ["value" => "canceled", "display" => "ABONADO"],
        ];


        $years = DateDimension::distinct()->get(['year']);

        $banks = Bank::all();

        $tiposCambios = $this->getTypeExchange();
        //dump($tiposCambios);
        $firstDayWeek = Carbon::now('America/Lima')->format('Y-m-d');
        //dump($firstDayWeek);
        $tipoCambio = $this->getExchange($firstDayWeek, $tiposCambios);
        $rate = $tipoCambio->compra;

        return view('financeWork.index_v2', compact( 'rate','years', 'permissions', 'arrayYears', 'arrayCustomers', 'arrayStateWorks', 'arrayStates', 'banks'));

    }

    public function exportFinanceWorks()
    {
        $begin = microtime(true);
        //dd($request);
        $start = $_GET['start'];
        $end = $_GET['end'];
        $rate = $_GET['rate'];
        //dump($start);
        //dump($end);
        $financeWorks_array = [];
        $dates = '';

        if ( $start == '' || $end == '' )
        {
            //dump('Descargar todos');
            $dates = 'INGRESOS CLIENTES';
            $financeWorks = FinanceWork::with('quote', 'bank')
                ->orderBy('created_at', 'DESC')->get();

            foreach ( $financeWorks as $work )
            {
                $firstWork = Work::where('quote_id', $work->quote_id)->first();

                $timeline = null;

                if ( isset($firstWork) )
                {
                    $timeline = Timeline::find($firstWork->timeline_id);
                }

                $state_work = $this->getStateWork($work->quote_id);

                $subtotal = (float)($work->quote->total_quote/1.18);
                $total = (float)($work->quote->total_quote);

                $igv =  ($total - $subtotal);

                $detraction = 0;
                $amount_detraction = 0;
                $detraction_text = '';
                $type = "";

                if ( $work->detraction == 'oc' )
                {
                    $detraction = 0.03;
                    if ( $work->quote->currency_invoice == "PEN" )
                    {
                        if ( $total >= 700 )
                        {
                            $amount_detraction = $total * $detraction;
                            $detraction_text = 'O.C. 3%';
                        } else {
                            $amount_detraction = 0;
                            $detraction_text = 'O.C. 3%';
                        }
                    } elseif ( $work->quote->currency_invoice == "USD" )
                    {
                        //
                        $typeExchange = (float)$rate;
                        $montoSoles = $total*$typeExchange;
                        if ( $montoSoles >= 700 )
                        {
                            $amount_detraction = $total * $detraction;
                            $detraction_text = 'O.C. 3%';
                        } else {
                            $amount_detraction = 0;
                            $detraction_text = 'O.C. 3%';
                        }
                    }
                    /*$amount_detraction = $total * $detraction;
                    $detraction_text = 'O.C. 3%';*/
                    $type = "OC";
                } elseif ( $work->detraction == 'os' )
                {
                    $detraction = 0.12;
                    if ( $work->quote->currency_invoice == "PEN" )
                    {
                        if ( $total >= 700 )
                        {
                            $amount_detraction = $total * $detraction;
                            $detraction_text = 'O.S. 12%';
                        } else {
                            $amount_detraction = 0;
                            $detraction_text = 'O.S. 12%';
                        }
                    } elseif ( $work->quote->currency_invoice == "USD" )
                    {
                        //
                        $typeExchange = (float)$rate;
                        $montoSoles = $total*$typeExchange;
                        if ( $montoSoles >= 700 )
                        {
                            $amount_detraction = $total * $detraction;
                            $detraction_text = 'O.S. 12%';
                        } else {
                            $amount_detraction = 0;
                            $detraction_text = 'O.S. 12%';
                        }
                    }
                    //$amount_detraction = $total * $detraction;
                    //$detraction_text = 'O.S. 12%';
                    $type = "OS";
                } else {
                    $detraction = 0;
                    $amount_detraction = $total * $detraction;
                    $detraction_text = 'N.N. 0%';
                    $type = "SIN ORDEN";
                }

                $act_of_acceptance = '';
                if ( $work->act_of_acceptance == 'pending' )
                {
                    $act_of_acceptance = 'PENDIENTE';
                } elseif ( $work->act_of_acceptance == 'generate' )
                {
                    $act_of_acceptance = 'GENERADA';
                } elseif ( $work->act_of_acceptance == 'not_generate' )
                {
                    $act_of_acceptance = 'NO GENERADA';
                }

                $state_act_of_acceptance = '';
                if ($work->state_act_of_acceptance == 'pending_signature')
                {
                    $state_act_of_acceptance = 'PENDIENTE DE FIRMA';
                } elseif ( $work->state_act_of_acceptance == 'signed' )
                {
                    $state_act_of_acceptance = 'FIRMADA';
                } elseif ( $work->state_act_of_acceptance == 'not_signed' )
                {
                    $state_act_of_acceptance = 'NO SE FIRMARÁ';
                }

                $state = '';
                if ($work->state == 'pending')
                {
                    $state = 'PENDIENTE DE ABONO';
                } elseif ( $work->state == 'canceled' )
                {
                    $state = 'ABONADO';
                }

                $state_invoiced = '';
                if ($work->invoiced == 'y')
                {
                    $state_invoiced = 'FACTURADO';
                } elseif ( $work->invoiced == 'n' )
                {
                    $state_invoiced = 'NO FACTURADO';
                }

                $advancement = '';
                if ($work->advancement == 'y')
                {
                    $advancement = 'SI';
                } elseif ( $work->advancement == 'n' )
                {
                    $advancement = 'NO';
                }

                $days =  ($work->quote->deadline == null) ? 0:$work->quote->deadline->days;

                $date_delivery = "No entregado";

                $currentDay = Carbon::now('America/Lima');
                $delivery_past = 'n';

                if ( $work->date_initiation == null )
                {
                    $date_initiation = ($timeline == null) ? 'No iniciado': $timeline->date->format('d/m/Y');
                } else {
                    $date_initiation = ($work->date_initiation == null) ? 'No iniciado':$work->date_initiation->format('d/m/Y');

                    if ( $work->date_initiation != null )
                    {
                        if ($work->quote->time_delivery != "")
                        {
                            $fecha_entrega = $work->date_initiation->addDays($work->quote->time_delivery);
                            $date_delivery = $fecha_entrega->format('d/m/Y');

                            $currentTimestamp = $currentDay->startOfDay()->timestamp;
                            $deliveryTimestamp = $fecha_entrega->startOfDay()->timestamp;

                            if ( ($deliveryTimestamp < $currentTimestamp) && $state_work != 'TERMINADO' )
                            {
                                $delivery_past = 's';
                            }
                        } else {
                            $date_delivery = "No especifica entrega";
                        }
                    } else {
                        $date_delivery = "No entregado";
                    }

                }

                $docier = "";

                if ($work->docier == null)
                {
                    $docier = 'SIN DOCIER';
                } elseif ($work->docier == 'pending')
                {
                    $docier = 'PENDIENTE DE FIRMAR';
                } elseif ($work->docier == 'signed')
                {
                    $docier = 'FIRMADA';
                }

                $discount_factoring = $work->discount_factoring;
                $year_paid = "";
                $month_paid = "";
                $revision = "";

                if ($work->revision == null)
                {
                    $revision = '';
                } elseif ($work->revision == 'pending')
                {
                    $revision = 'PENDIENTE';
                } elseif ($work->revision == 'revised')
                {
                    $revision = 'REVISADO';
                }

                array_push($financeWorks_array, [
                    "id" => $work->id,
                    "year" => $work->raise_date->year,
                    "customer" => ($work->quote->customer == null) ? 'Sin contacto': $work->quote->customer->business_name,
                    "responsible" => ($work->quote->contact == null) ? 'Sin contacto': $work->quote->contact->name,
                    "area" => ($work->quote->contact == null || ($work->quote->contact != null && $work->quote->contact->area == "")) ? 'Sin área': $work->quote->contact->area,
                    "type" => $type,
                    "initiation" => $date_initiation,
                    "delivery" => $date_delivery,
                    "quote" => $work->quote->id . "-" . $work->raise_date->year,
                    "order_customer" => $work->quote->code_customer,
                    "description" => $work->quote->description_quote,
                    "state_work" => $state_work,
                    "act_of_acceptance" => $act_of_acceptance,
                    "state_act_of_acceptance" => $state_act_of_acceptance,
                    "pay_condition" => ($work->quote->deadline == null) ? '':$work->quote->deadline->description,
                    "advancement" => $advancement,
                    "amount_advancement" => $work->amount_advancement,
                    "subtotal" => round($subtotal, 2),
                    "igv" => round($igv, 2),
                    "total" => round($total, 2),
                    "detraction" => $detraction_text,
                    "amount_detraction" => round($amount_detraction, 2),
                    "discount_factoring" => round($discount_factoring, 2),
                    "amount_include_detraction" => round($total - $amount_detraction - $discount_factoring, 2),
                    "invoiced" => $state_invoiced,
                    "number_invoice" => $work->number_invoice,
                    "year_invoice" => ($work->year_invoice == null) ? $this->obtenerYearInvoice($work) : $work->year_invoice,
                    "month_invoice" => ($work->month_invoice == null) ? $this->obtenerMonthInvoice($work): $this->obtenerNombreMes($work->month_invoice),
                    "date_issue" => ($work->date_issue == null) ? 'Sin fecha' : $work->date_issue->format('d/m/Y'),
                    "date_admission" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->format('d/m/Y'),
                    "days" => $days,
                    "date_programmed" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->addDays($days)->format('d/m/Y'),
                    "bank" => ($work->bank == null) ? '' : $work->bank->short_name,
                    "state" => $state,
                    "year_paid" => ($work->year_paid == null) ? $this->obtenerYearPaid($work) : $work->year_paid,
                    "month_paid" => ($work->month_paid == null) ? $this->obtenerMonthPaid($work): $this->obtenerNombreMes($work->month_paid),
                    "date_paid" => ($work->date_paid == null) ? 'Sin fecha' : $work->date_paid->format('d/m/Y'),
                    "observation" => $work->observation,
                    "docier" => $docier,
                    "hes" => ($work->hes == null) ? 'PENDIENTE': $work->hes,
                    "revision" => $revision,
                    "delivery_past" => $delivery_past,
                    "currency" => $work->quote->currency_invoice,
                ]);
            }


        } else {
            $date_start = Carbon::createFromFormat('d/m/Y', $start);
            $end_start = Carbon::createFromFormat('d/m/Y', $end);

            $dates = 'INGRESOS CLIENTES DEL '. $start .' AL '. $end;
            $financeWorks = FinanceWork::with('quote', 'bank')
                ->whereHas('quote', function ($query2) use ($date_start, $end_start) {
                    $query2->whereDate('date_quote', '>=', $date_start)
                        ->whereDate('date_quote', '<=', $end_start);
                })
                ->orderBy('created_at', 'DESC');

            foreach ( $financeWorks as $work )
            {
                $firstWork = Work::where('quote_id', $work->quote_id)->first();

                $timeline = null;

                if ( isset($firstWork) )
                {
                    $timeline = Timeline::find($firstWork->timeline_id);
                }

                $state_work = $this->getStateWork($work->quote_id);

                $subtotal = (float)($work->quote->total_quote/1.18);
                $total = (float)($work->quote->total_quote);

                $igv =  ($total - $subtotal);

                $detraction = 0;
                $amount_detraction = 0;
                $detraction_text = '';
                $type = "";

                if ( $work->detraction == 'oc' )
                {
                    $detraction = 0.03;
                    $amount_detraction = $total * $detraction;
                    $detraction_text = 'O.C. 3%';
                    $type = "OC";
                } elseif ( $work->detraction == 'os' )
                {
                    $detraction = 0.12;
                    $amount_detraction = $total * $detraction;
                    $detraction_text = 'O.S. 12%';
                    $type = "OS";
                } else {
                    $detraction = 0;
                    $amount_detraction = $total * $detraction;
                    $detraction_text = 'N.N. 0%';
                    $type = "SIN ORDEN";
                }

                $act_of_acceptance = '';
                if ( $work->act_of_acceptance == 'pending' )
                {
                    $act_of_acceptance = 'PENDIENTE';
                } elseif ( $work->act_of_acceptance == 'generate' )
                {
                    $act_of_acceptance = 'GENERADA';
                } elseif ( $work->act_of_acceptance == 'not_generate' )
                {
                    $act_of_acceptance = 'NO GENERADA';
                }

                $state_act_of_acceptance = '';
                if ($work->state_act_of_acceptance == 'pending_signature')
                {
                    $state_act_of_acceptance = 'PENDIENTE DE FIRMA';
                } elseif ( $work->state_act_of_acceptance == 'signed' )
                {
                    $state_act_of_acceptance = 'FIRMADA';
                } elseif ( $work->state_act_of_acceptance == 'not_signed' )
                {
                    $state_act_of_acceptance = 'NO SE FIRMARÁ';
                }

                $state = '';
                if ($work->state == 'pending')
                {
                    $state = 'PENDIENTE DE ABONO';
                } elseif ( $work->state == 'canceled' )
                {
                    $state = 'ABONADO';
                }

                $state_invoiced = '';
                if ($work->invoiced == 'y')
                {
                    $state_invoiced = 'FACTURADO';
                } elseif ( $work->invoiced == 'n' )
                {
                    $state_invoiced = 'NO FACTURADO';
                }

                $advancement = '';
                if ($work->advancement == 'y')
                {
                    $advancement = 'SI';
                } elseif ( $work->advancement == 'n' )
                {
                    $advancement = 'NO';
                }

                $days =  ($work->quote->deadline == null) ? 0:$work->quote->deadline->days;

                $date_delivery = "No entregado";

                $currentDay = Carbon::now('America/Lima');
                $delivery_past = 'n';

                if ( $work->date_initiation == null )
                {
                    $date_initiation = ($timeline == null) ? 'No iniciado': $timeline->date->format('d/m/Y');
                } else {
                    $date_initiation = ($work->date_initiation == null) ? 'No iniciado':$work->date_initiation->format('d/m/Y');

                    if ( $work->date_initiation != null )
                    {
                        if ($work->quote->time_delivery != "")
                        {
                            $fecha_entrega = $work->date_initiation->addDays($work->quote->time_delivery);
                            $date_delivery = $fecha_entrega->format('d/m/Y');

                            $currentTimestamp = $currentDay->startOfDay()->timestamp;
                            $deliveryTimestamp = $fecha_entrega->startOfDay()->timestamp;

                            if ( ($deliveryTimestamp < $currentTimestamp) && $state_work != 'TERMINADO' )
                            {
                                $delivery_past = 's';
                            }
                        } else {
                            $date_delivery = "No especifica entrega";
                        }
                    } else {
                        $date_delivery = "No entregado";
                    }

                }

                $docier = "";

                if ($work->docier == null)
                {
                    $docier = 'SIN DOCIER';
                } elseif ($work->docier == 'pending')
                {
                    $docier = 'PENDIENTE DE FIRMAR';
                } elseif ($work->docier == 'signed')
                {
                    $docier = 'FIRMADA';
                }

                $discount_factoring = $work->discount_factoring;
                $year_paid = "";
                $month_paid = "";
                $revision = "";

                if ($work->revision == null)
                {
                    $revision = '';
                } elseif ($work->revision == 'pending')
                {
                    $revision = 'PENDIENTE';
                } elseif ($work->revision == 'revised')
                {
                    $revision = 'REVISADO';
                }

                array_push($financeWorks_array, [
                    "id" => $work->id,
                    "year" => $work->raise_date->year,
                    "customer" => ($work->quote->customer == null) ? 'Sin contacto': $work->quote->customer->business_name,
                    "responsible" => ($work->quote->contact == null) ? 'Sin contacto': $work->quote->contact->name,
                    "area" => ($work->quote->contact == null || ($work->quote->contact != null && $work->quote->contact->area == "")) ? 'Sin área': $work->quote->contact->area,
                    "type" => $type,
                    "initiation" => $date_initiation,
                    "delivery" => $date_delivery,
                    "quote" => $work->quote->id . "-" . $work->raise_date->year,
                    "order_customer" => $work->quote->code_customer,
                    "description" => $work->quote->description_quote,
                    "state_work" => $state_work,
                    "act_of_acceptance" => $act_of_acceptance,
                    "state_act_of_acceptance" => $state_act_of_acceptance,
                    "pay_condition" => ($work->quote->deadline == null) ? '':$work->quote->deadline->description,
                    "advancement" => $advancement,
                    "amount_advancement" => $work->amount_advancement,
                    "subtotal" => number_format($subtotal, 2),
                    "igv" => number_format($igv, 2),
                    "total" => number_format($total, 2),
                    "detraction" => $detraction_text,
                    "amount_detraction" => number_format($amount_detraction, 2),
                    "discount_factoring" => number_format($discount_factoring, 2),
                    "amount_include_detraction" => number_format($total - $amount_detraction - $discount_factoring, 2),
                    "invoiced" => $state_invoiced,
                    "number_invoice" => $work->number_invoice,
                    "year_invoice" => ($work->year_invoice == null) ? $this->obtenerYearInvoice($work) : $work->year_invoice,
                    "month_invoice" => ($work->month_invoice == null) ? $this->obtenerMonthInvoice($work): $this->obtenerNombreMes($work->month_invoice),
                    "date_issue" => ($work->date_issue == null) ? 'Sin fecha' : $work->date_issue->format('d/m/Y'),
                    "date_admission" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->format('d/m/Y'),
                    "days" => $days,
                    "date_programmed" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->addDays($days)->format('d/m/Y'),
                    "bank" => ($work->bank == null) ? '' : $work->bank->short_name,
                    "state" => $state,
                    "year_paid" => ($work->year_paid == null) ? $this->obtenerYearPaid($work) : $work->year_paid,
                    "month_paid" => ($work->month_paid == null) ? $this->obtenerMonthPaid($work): $this->obtenerNombreMes($work->month_paid),
                    "date_paid" => ($work->date_paid == null) ? 'Sin fecha' : $work->date_paid->format('d/m/Y'),
                    "observation" => $work->observation,
                    "docier" => $docier,
                    "hes" => ($work->hes == null) ? 'PENDIENTE': $work->hes,
                    "revision" => $revision,
                    "delivery_past" => $delivery_past
                ]);
            }

        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Reporte Excel Ingresos Clientes',
            'time' => $end
        ]);

        return (new FinanceWorksExport($financeWorks_array, $dates))->download('ingresosClientes.xlsx');

    }
}
