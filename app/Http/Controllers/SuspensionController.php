<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\ReasonSuspension;
use App\Suspension;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuspensionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('suspension.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        $reasons = ReasonSuspension::select('id', 'reason', 'days')->get();

        return view('suspension.create', compact('workers', 'reasons'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $current = Carbon::now('America/Lima');
            $fecha = $current->year.'/'.$current->month.'/'.$current->day;
            $reason = ReasonSuspension::find($request->get('reason_id'));
            $date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : Carbon::createFromFormat('d/m/Y', $fecha);
            $date_end = $date_start->addDays($reason->days-1);
            $suspension = Suspension::create([
                'reason_suspension_id' => $reason->id,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'worker_id' => $request->get('worker_id'),
            ]);

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$suspension->date_start)
                ->whereDate('date_assistance', '<=',$suspension->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $suspension->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        /*$workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();*/
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'S';
                            $assistanceDetail->justification = null;
                            $assistanceDetail->obs_justification = null;
                            $assistanceDetail->working_day_id = $workingDay->id;
                            $assistanceDetail->save();
                        }
                    }
                }
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Suspensión guardada con éxito.'], 200);

    }

    public function edit($suspension_id)
    {
        $reasons = ReasonSuspension::select('id', 'reason', 'days')->get();

        $suspension = Suspension::with('worker')
            ->with('reason')
            ->find($suspension_id);

        return view('suspension.edit', compact('suspension', 'reasons'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $current = Carbon::now('America/Lima');
            $fecha = $current->year.'/'.$current->month.'/'.$current->day;
            $reason = ReasonSuspension::find($request->get('reason_id'));
            $date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : Carbon::createFromFormat('d/m/Y', $fecha);
            $date_end = $date_start->addDays($reason->days-1);

            $suspension = Suspension::find($request->get('suspension_id'));

            $suspension->reason_suspension_id = $request->get('reason_id');
            $suspension->date_start = $date_start;
            $suspension->date_end = $date_end;
            $suspension->save();

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$suspension->date_start)
                ->whereDate('date_assistance', '<=',$suspension->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $suspension->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        /*$workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();*/
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'S';
                            $assistanceDetail->justification = null;
                            $assistanceDetail->obs_justification = null;
                            $assistanceDetail->working_day_id = $workingDay->id;
                            $assistanceDetail->save();
                        }
                    }
                }
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Suspensión modificada con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $suspension = Suspension::find($request->get('suspension_id'));

            $suspension->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Suspensión eliminada con éxito.'], 200);

    }

    public function getAllSuspensions()
    {
        $suspensions = Suspension::select('id', 'date_start', 'date_end', 'worker_id', 'created_at', 'reason_suspension_id')
            ->with('worker')
            ->with('reason')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($suspensions)->toJson();

    }
}
