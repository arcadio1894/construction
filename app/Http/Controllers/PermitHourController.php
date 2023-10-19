<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\PermitHour;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermitHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('permithour.index', compact('permissions'));
    }
    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('permithour.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $permitHour = PermitHour::create([
                'reason' => $request->get('reason'),
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'hour' => $request->get('hour'),
                'worker_id' => $request->get('worker_id'),
            ]);

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '=',$permitHour->date_start)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permitHour->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'PH';
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
        return response()->json(['message' => 'Permiso guardado con éxito.'], 200);

    }

    public function edit($permitHour_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $permitHour = PermitHour::with('worker')->find($permitHour_id);

        return view('permithour.edit', compact('permit_Hour', 'workers'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $permitHour = PermitHour::find($request->get('permitHour_id'));

            $permitHour->reason = $request->get('reason');
            $permitHour->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $permitHour->hour = $request->get('hour');
            $permitHour->save();


            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '=',$permitHour->date_start)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permitHour->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {

                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'PH';
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
        return response()->json(['message' => 'Permiso por horas modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $permitHour = PermitHour::find($request->get('permitHour_id'));

            $permitHour->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso por hora eliminado con éxito.'], 200);

    }

    public function getAllPermits()
    {
        $permits = PermitHour::select('id', 'date_start', 'hour', 'worker_id', 'created_at', 'reason')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($permits)->toJson();

    }
}
