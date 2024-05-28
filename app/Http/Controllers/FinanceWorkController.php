<?php

namespace App\Http\Controllers;

use App\Audit;
use App\Bank;
use App\Customer;
use App\DateDimension;
use App\Exports\FinanceWorksExport;
use App\FinanceWork;
use App\Output;
use App\OutputDetail;
use App\Quote;
use App\Services\TipoCambioService;
use App\Timeline;
use App\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceWorkController extends Controller
{
    protected $tipoCambioService;

    public function __construct(TipoCambioService $tipoCambioService)
    {
        $this->tipoCambioService = $tipoCambioService;
    }

    public function getDataFinanceWorks(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $description = $request->input('description');
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
        $rate = $request->input('rate');

        if ( $startDate == "" || $endDate == "" )
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
        }

        //$query = FinanceWork::with('quote', 'bank');

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $finance_works = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        //dd($proformas);

        $array = [];

        foreach ( $finance_works as $work )
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

            array_push($array, [
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
                "delivery_past" => $delivery_past,
                "currency" => $work->quote->currency_invoice,
            ]);
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
        $firstDayWeek = Carbon::now('America/Lima');
        $fechaformateada = $firstDayWeek->format('Y-m-d');
        //dump($firstDayWeek);
        $tipoCambio = $this->getExchange($fechaformateada, $tiposCambios);
        if ($tipoCambio == null)
        {
            $firstDayWeek->subDays(1);
            $fechaformateada = $firstDayWeek->format('Y-m-d');
            $tipoCambio = $this->getExchange($fechaformateada, $tiposCambios);
        }
        $rate = $tipoCambio->precioCompra;

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

    public function createFinanceWorks()
    {
        $quotes = Quote::where(function ($query) {
            $query->where('state_active', 'open')
                ->orWhere('state_active', 'close');
        })
            ->where('state', 'confirmed')
            ->where('raise_status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ( $quotes as $quote )
        {
            $fw = FinanceWork::where('quote_id', $quote->id)->first();
            if ( !isset( $fw ) )
            {
                $financeWork = FinanceWork::create([
                    'quote_id' => $quote->id,
                    'raise_date' => $quote->updated_at, // Cuando se eleva la cotizacion debe guardarse este dato
                    'date_delivery' => null,
                    'act_of_acceptance' => 'pending',
                    'state_act_of_acceptance' => null,
                    'advancement' => 'n',
                    'amount_advancement' => 0,
                    'detraction' => null,
                    'invoiced' => 'n',
                    'number_invoice' => null,
                    'month_invoice' => null,
                    'date_issue' => null,
                    'date_admission' => null,
                    'bank_id' => null,
                    'state' => 'pending',
                    'date_paid' => null,
                    'observation' => null
                ]);
            }

        }

        return response()->json(["message" => "Finance Works generados correctamente"]);
    }

    public function getExchange($fecha, $tiposCambios)
    {
        $date = Carbon::createFromFormat('Y-m-d', $fecha);
        $dateCurrent = Carbon::now('America/Lima');

        if ( $date->lessThan($dateCurrent) )
        {
            // Buscar el elemento en la data que tenga la fecha indicada
            $elementoEncontrado = null;
            $elementoEncontrado = null;
            foreach ($tiposCambios as $elemento) {
                if ($elemento->fecha === $fecha) {
                    $elementoEncontrado = $elemento;
                    break; // Rompemos el loop si encontramos el elemento
                }
            }

            // Verificar si se encontró el elemento y hacer lo que necesites con él
            if ($elementoEncontrado) {
                // Aquí tienes el elemento que corresponde a la fecha buscada
                // Puedes imprimirlo o acceder a sus valores individuales
                $tipoCambioSunat = $elementoEncontrado;
            } else {
                // El elemento no fue encontrado
                $tipoCambioSunat = [];
            }

            return $tipoCambioSunat;
        } else {
            //dump('No entre');
            return null;
        }
    }

    public function getTypeExchange()
    {
        $currentDay = Carbon::now('America/Lima');
        //dump($currentDay->year);
        //dd($currentDay->month);
        //$token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        //$token = 'apis-token-8651.OrHQT9azFQteF-IhmcLXP0W2MkemnPNX';
        /*$token = env('TOKEN_DOLLAR');
        $curl = curl_init();*/

        /*curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?year='.$currentDay->year.'&month='.$currentDay->month,
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
        ));*/
        /*curl_setopt_array($curl, array(
            // para usar la api versión 2
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sbs/tipo-cambio?month='.$currentDay->month.'&year='.$currentDay->year,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
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

        if ($response === false) {
            // Error en la ejecución de cURL
            $errorNo = curl_errno($curl);
            $errorMsg = curl_error($curl);
            curl_close($curl);

            // Manejar el error
            //echo "cURL Error #{$errorNo}: {$errorMsg}";
        } else {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode >= 200 && $httpCode < 300) {
                // La solicitud fue exitosa
                $data = json_decode($response, true);
                // Manejar la respuesta exitosa
                //echo "Respuesta exitosa: ";
                //print_r($data);
            } else {
                // La solicitud no fue exitosa
                // Decodificar el mensaje de error si la respuesta está en formato JSON
                $errorData = json_decode($response, true);
                //echo "Error en la solicitud: ";
                ///print_r($errorData);
                $response = [
                    "precioCompra"=> 3.738,
                    "precioVenta"=> 3.746,
                    "moneda"=> "USD",
                    "fecha"=> "2024-05-24"
                ];
            }
        }*/

        $tipoCambio = $this->tipoCambioService->obtenerPorMonthYear($currentDay->month, $currentDay->year);
        return response()->json($tipoCambio);

        //curl_close($curl);

        //$tipoCambioSunat = json_decode($response);

        //dd($response);

        //return $tipoCambio;
    }

    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $registros = FinanceWork::all();

        $arrayYears = $registros->pluck('raise_date')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $arrayYears = array_values($arrayYears);

        $arrayCustomers = Customer::select('id', 'business_name')->get()->toArray();

        $arrayStateWorks = ['POR INICIAR', 'EN PROCESO', 'TERMINADO', 'PAUSADO', 'CANCELADO'];

        $arrayStates = ['PENDIENTE DE ABONO', 'ABONADO'];

        $years = DateDimension::distinct()->get(['year']);

        $banks = Bank::all();

        return view('financeWork.index', compact( 'years', 'permissions', 'arrayYears', 'arrayCustomers', 'arrayStateWorks', 'arrayStates', 'banks'));
    }

    public function getFinanceWorks()
    {
        $financeWorks = FinanceWork::with('quote', 'bank')->get();

        $current_date = "";

        $array = [];
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

            if ( $work->date_initiation == null )
            {
                $date_initiation = ($timeline == null) ? 'No iniciado': $timeline->date->format('d/m/Y');
            } else {
                $date_initiation = ($work->date_initiation == null) ? 'No iniciado':$work->date_initiation->format('d/m/Y');

                if ( $work->date_initiation != null )
                {
                    if ($work->quote->time_delivery != "")
                    {
                        $date_delivery = $work->date_initiation->addDays($work->quote->time_delivery)->format('d/m/Y');
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

            array_push($array, [
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
                "revision" => $revision
            ]);
        }

        /*dump($array);
        dd();*/

        return datatables($array)->toJson();
    }

    public function obtenerFechaEntrega($work)
    {
        $date_delivery = "";

        if ( $work->date_initiation == null )
        {
            $date_delivery = "";
        } else {

            if ($work->quote->time_delivery != "")
            {
                $date_delivery = $work->date_initiation->addDays($work->quote->time_delivery)->format('d/m/Y');
            } else {
                $date_delivery = "";
            }
        }

        return $date_delivery;
    }

    public function obtenerMonthPaid($work)
    {
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

        // TODO: Ingresamos porque no hay month_invoice
        // TODO: Verificamos si existe el date_issue
        if (isset($work->date_paid))
        {
            // TODO: Si existe el date_issue entonces obtenemos el mes de la fecha auto
            $numeroMes = $work->date_paid->month;
            return isset($meses[$numeroMes]) ? $meses[$numeroMes] : 'Mes inválido';
        } else {
            return "";
        }
    }

    public function obtenerYearPaid($work)
    {
        // TODO: Ingresamos porque no hay year_paid
        // TODO: Verificamos si existe el date_paid
        if (isset($work->date_paid))
        {
            // TODO: Si existe el date_issue entonces obtenemos el año de la fecha auto
            $year = $work->date_paid->year;
            return $year;
        } else {
            return "";
        }
    }

    public function obtenerYearInvoice($work)
    {
        // TODO: Ingresamos porque no hay year_invoice
        // TODO: Verificamos si existe el date_issue
        if (isset($work->date_issue))
        {
            // TODO: Si existe el date_issue entonces obtenemos el año de la fecha auto
            $year = $work->date_issue->year;
            return $year;
        } else {
            return "";
        }
    }

    public function obtenerMonthInvoice($work)
    {
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

        // TODO: Ingresamos porque no hay month_invoice
        // TODO: Verificamos si existe el date_issue
        if (isset($work->date_issue))
        {
            // TODO: Si existe el date_issue entonces obtenemos el mes de la fecha auto
            $numeroMes = $work->date_issue->month;
            return isset($meses[$numeroMes]) ? $meses[$numeroMes] : 'Mes inválido';
        } else {
            return "";
        }

    }

    public function getStateWork($quote_id)
    {
        $state_work = '';
        $quote = Quote::find($quote_id);

        $outputs = OutputDetail::where('quote_id', $quote_id)->get();

        $finance_work = FinanceWork::where('quote_id', $quote_id)->first();

        if ( $finance_work->state_work == null )
        {
            if ( $quote->state == 'canceled' )
            {
                $state_work = 'CANCELADO';
            } elseif ( $quote->state_active == 'close' )
            {
                $state_work = 'TERMINADO';
            } elseif ( count($outputs) == 0 )
            {
                $state_work = 'POR INICIAR';
            } elseif (  count($outputs) > 0 )
            {
                $state_work = 'EN PROCESO';
            }
        } else {
            if ( $finance_work->state_work == 'finished' )
            {
                $state_work = 'TERMINADO';
            } elseif ( $finance_work->state_work == 'to_start' )
            {
                $state_work = 'POR INICIAR';
            } elseif (  $finance_work->state_work == 'in_progress' )
            {
                $state_work = 'EN PROCESO';
            } elseif (  $finance_work->state_work == 'stopped' )
            {
                $state_work = 'PAUSADO';
            } elseif (  $finance_work->state_work == 'canceled' )
            {
                $state_work = 'CANCELADO';
            }
        }

        return $state_work;
    }

    public function getStateWorkCode($quote_id)
    {
        $state_work = '';
        $quote = Quote::find($quote_id);

        $outputs = OutputDetail::where('quote_id', $quote_id)->get();

        $finance_work = FinanceWork::where('quote_id', $quote_id)->first();

        if ( $finance_work->state_work == null )
        {
            if ( $quote->state == 'canceled' )
            {
                $state_work = 'canceled';
            } elseif ( $quote->state_active == 'close' )
            {
                $state_work = 'finished';
            } elseif ( count($outputs) == 0 )
            {
                $state_work = 'to_start';
            } elseif (  count($outputs) > 0 )
            {
                $state_work = 'in_progress';
            }
        } else {
            $state_work = $finance_work->state_work;
        }

        return $state_work;
    }

    public function getInfoTrabajoFinanceWork($financeWork_id)
    {
        $financeWork = FinanceWork::find($financeWork_id);

        $firstWork = Work::where('quote_id', $financeWork->quote_id)->first();

        $timeline = null;

        if ( isset($firstWork) )
        {
            $timeline = Timeline::find($firstWork->timeline_id);
        }

        if ( $financeWork->date_initiation == null )
        {
            $date_initiation = ($timeline == null) ? '': $timeline->date->format('d/m/Y');
        } else {
            $date_initiation = ($financeWork->date_initiation == null) ? '':$financeWork->date_initiation->format('d/m/Y');
        }

        return response()->json([
            "state_work" => $this->getStateWorkCode($financeWork->quote->id),
            "customer_id" => $financeWork->quote->customer_id,
            "contact_id" => $financeWork->quote->contact_id,
            "date_initiation" => $date_initiation,
            "date_delivery" => $this->obtenerFechaEntrega($financeWork),
            "detraction" => $financeWork->detraction,
            "act_of_acceptance" => $financeWork->act_of_acceptance,
            "state_act_of_acceptance" => $financeWork->state_act_of_acceptance,
            "docier" => ($financeWork->docier == null) ? 'nn': $financeWork->docier,
            "hes" => ($financeWork->hes == null) ? '': $financeWork->hes,
        ]);
    }

    public function financeWorkEditTrabajo( Request $request )
    {
        DB::beginTransaction();
        try {

            $financeWork = FinanceWork::find($request->get('financeWork_id'));

            if ( !isset($financeWork) )
            {
                return response()->json(['message' => "No se encuentra ID enviado"], 422);
            }

            $quote = Quote::find($financeWork->quote_id);

            $quote->customer_id = $request->get('customer_id');
            $quote->contact_id = $request->get('contact_id');
            $quote->save();

            $financeWork->date_initiation = ($request->get('date_initiation') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_initiation')) : null;
            $financeWork->date_delivery = ($request->get('date_delivery') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_delivery')) : null;
            $financeWork->detraction = ($request->get('detraction') == 'nn' || $request->get('detraction') == '' ) ? null: $request->get('detraction');
            $financeWork->act_of_acceptance = ($request->get('act_of_acceptance') == 'nn' || $request->get('act_of_acceptance') == '' ) ? 'pending': $request->get('act_of_acceptance');
            $financeWork->state_act_of_acceptance = ($request->get('state_act_of_acceptance') == 'nn' || $request->get('state_act_of_acceptance') == '' ) ? null: $request->get('state_act_of_acceptance');
            $financeWork->state_work = ($request->get('state_work') == 'nn' || $request->get('state_work') == '' ) ? null: $request->get('state_work');
            $financeWork->docier = ($request->get('docier') == 'nn' || $request->get('docier') == '' ) ? null: $request->get('docier');
            $financeWork->hes = ($request->get('hes') == '' ) ? null: $request->get('hes');
            $financeWork->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Información del trabajo modificado con éxito.'], 200);

    }

    public function getInfoFacturacionFinanceWork($financeWork_id)
    {
        $financeWork = FinanceWork::find($financeWork_id);

        return response()->json([
            "advancement" => $financeWork->advancement,
            "amount_advancement" => $financeWork->amount_advancement,
            "invoiced" => $financeWork->invoiced,
            "number_invoice" => $financeWork->number_invoice,
            "year_invoice" => ($financeWork->year_invoice == null) ? $this->obtenerYearInvoice($financeWork) : $financeWork->year_invoice,
            "month_invoice" => ($financeWork->month_invoice == null) ? $this->obtenerMonthInvoiceGet($financeWork): $financeWork->month_invoice,
            "date_issue" => ($financeWork->date_issue == null) ? '': $financeWork->date_issue->format('d/m/Y'),
            "date_admission" => ($financeWork->date_admission == null) ? '': $financeWork->date_admission->format('d/m/Y'),
            "bank_id" => $financeWork->bank_id,
            "state" => $financeWork->state,
            "year_paid" => ($financeWork->year_paid == null) ? $this->obtenerYearPaid($financeWork) : $financeWork->year_paid,
            "month_paid" => ($financeWork->month_paid == null) ? $this->obtenerMonthPaidGet($financeWork): $financeWork->month_paid,
            "date_paid" => ($financeWork->date_paid == null) ? '': $financeWork->date_paid->format('d/m/Y'),
            "observation" => $financeWork->observation,
            "discount_factoring" => $financeWork->discount_factoring,
            "revision" => $financeWork->revision
        ]);
    }

    public function obtenerMonthPaidGet($work)
    {
        // TODO: Ingresamos porque no hay month_invoice
        // TODO: Verificamos si existe el date_issue
        if (isset($work->date_paid))
        {
            // TODO: Si existe el date_issue entonces obtenemos el mes de la fecha auto
            $numeroMes = $work->date_paid->month;
            return isset($numeroMes) ? $numeroMes : '';
        } else {
            return "";
        }
    }

    public function obtenerMonthInvoiceGet($work)
    {
        // TODO: Ingresamos porque no hay month_invoice
        // TODO: Verificamos si existe el date_issue
        if (isset($work->date_issue))
        {
            // TODO: Si existe el date_issue entonces obtenemos el mes de la fecha auto
            $numeroMes = $work->date_issue->month;
            return isset($numeroMes) ? $numeroMes : '';
        } else {
            return "";
        }
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

    public function financeWorkEditFacturacion( Request $request )
    {
        DB::beginTransaction();
        try {

            $financeWork = FinanceWork::find($request->get('financeWork_id'));

            if ( !isset($financeWork) )
            {
                return response()->json(['message' => "No se encuentra ID enviado"], 422);
            }

            $financeWork->advancement = ($request->get('advancement') == 'y') ? 'y':'n';
            $financeWork->amount_advancement = $request->get('amount_advancement');
            $financeWork->invoiced = ($request->get('invoiced') == 'y') ? 'y':'n';
            $financeWork->discount_factoring = $request->get('discount_factoring');
            if ( $request->get('invoiced') == 'y' )
            {
                $financeWork->number_invoice = $request->get('number_invoice');
                $financeWork->month_invoice = $request->get('month_invoice');
                $financeWork->year_invoice = $request->get('year_invoice');
                $financeWork->date_issue = ($request->get('date_issue') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_issue')) : null;
                $financeWork->date_admission = ($request->get('date_admission') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_admission')) : null;
                $financeWork->bank_id = $request->get('bank_id');
            }

            $financeWork->state = $request->get('state');
            $financeWork->month_paid = $request->get('month_paid');
            $financeWork->year_paid = $request->get('year_paid');
            $financeWork->date_paid = ($request->get('date_paid') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_paid')) : null;
            $financeWork->observation = $request->get('observation');
            $financeWork->revision = ($request->get('revision') == null || $request->get('revision') == "") ? null: $request->get('revision');
            $financeWork->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Información de Facturación modificado con éxito.'], 200);

    }
}
