<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Customer;
use App\FinanceWork;
use App\Output;
use App\OutputDetail;
use App\Quote;
use App\Timeline;
use App\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceWorkController extends Controller
{
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

        $arrayStateWorks = ['POR INICIAR', 'EN PROCESO', 'TERMINADO'];

        $arrayStates = ['PENDIENTE', 'CANCELADO'];

        $banks = Bank::all();

        return view('financeWork.index', compact( 'permissions', 'arrayYears', 'arrayCustomers', 'arrayStateWorks', 'arrayStates', 'banks'));
    }

    public function getFinanceWorks()
    {
        $financeWorks = FinanceWork::with('quote', 'bank')->get();

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
                $state = 'PENDIENTE';
            } elseif ( $work->state == 'canceled' )
            {
                $state = 'CANCELADO';
            }

            $state_invoiced = '';
            if ($work->invoiced == 'y')
            {
                $state_invoiced = 'SI';
            } elseif ( $work->invoiced == 'n' )
            {
                $state_invoiced = 'NO';
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
                "amount_include_detraction" => number_format($total - $amount_detraction, 2),
                "invoiced" => $state_invoiced,
                "number_invoice" => $work->number_invoice,
                "month_invoice" => ($work->month_invoice == null) ? '': $this->obtenerNombreMes($work->month_invoice),
                "date_issue" => ($work->date_issue == null) ? 'Sin fecha' : $work->date_issue->format('d/m/Y'),
                "date_admission" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->format('d/m/Y'),
                "days" => $days,
                "date_programmed" => ($work->date_admission == null) ? 'Sin fecha' : $work->date_admission->addDays($days)->format('d/m/Y'),
                "bank" => ($work->bank == null) ? '' : $work->bank->short_name,
                "state" => $state,
                "date_paid" => ($work->date_paid == null) ? 'Sin fecha' : $work->date_paid->format('d/m/Y'),
                "observation" => $work->observation
            ]);
        }

        /*dump($array);
        dd();*/

        return datatables($array)->toJson();
    }

    public function getStateWork($quote_id)
    {
        $state_work = '';
        $quote = Quote::find($quote_id);

        $outputs = OutputDetail::where('quote_id', $quote_id)->get();

        $finance_work = FinanceWork::where('quote_id', $quote_id)->first();

        if ( $finance_work->state_work == null )
        {
            if ( $quote->state_active == 'close' )
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
            if ( $quote->state_active == 'close' )
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
            "date_delivery" => ($financeWork->date_delivery == null) ? '': $financeWork->date_delivery->format('d/m/Y'),
            "detraction" => $financeWork->detraction,
            "act_of_acceptance" => $financeWork->act_of_acceptance,
            "state_act_of_acceptance" => $financeWork->state_act_of_acceptance
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
            "month_invoice" => $financeWork->month_invoice,
            "date_issue" => ($financeWork->date_issue == null) ? '': $financeWork->date_issue->format('d/m/Y'),
            "date_admission" => ($financeWork->date_admission == null) ? '': $financeWork->date_admission->format('d/m/Y'),
            "bank_id" => $financeWork->bank_id,
            "state" => $financeWork->state,
            "date_paid" => ($financeWork->date_paid == null) ? '': $financeWork->date_paid,
            "observation" => $financeWork->observation
        ]);
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

            if ( $request->get('invoiced') == 'y' )
            {
                $financeWork->number_invoice = $request->get('number_invoice');
                $financeWork->month_invoice = $request->get('month_invoice');
                $financeWork->date_issue = ($request->get('date_issue') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_issue')) : null;
                $financeWork->date_admission = ($request->get('date_admission') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_admission')) : null;
                $financeWork->bank_id = $request->get('bank_id');
            }

            $financeWork->state = $request->get('state');
            $financeWork->date_paid = ($request->get('date_paid') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_paid')) : null;
            $financeWork->observation = $request->get('observation');
            $financeWork->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Información de Facturación modificado con éxito.'], 200);

    }
}
