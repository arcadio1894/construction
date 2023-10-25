<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\Http\Requests\PermitHourDestroyRequest;
use App\Http\Requests\PermitHourStoreRequest;
use App\Http\Requests\PermitHourUpdateRequest;
use App\PermitHour;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
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

    public function store(PermitHourStoreRequest $request)
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
        return response()->json(['message' => 'Permiso por hora guardado con éxito.'], 200);

    }

    public function edit($permitHour_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $permitHour = PermitHour::with('worker')->find($permitHour_id);


        return view('permithour.edit', compact('permitHour', 'workers'));

    }
    public function update(PermitHourUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $permitHour = PermitHour::find($request->get('permitHour_id'));
            $date_start_db = $permitHour->date_start;

            $permitHour->reason = $request->get('reason');
            $new_date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $permitHour->date_start = $new_date_start;
            $permitHour->hour = $request->get('hour');
            $permitHour->save();

            if ($date_start_db < $new_date_start) {
                $assistancesA = Assistance::whereDate('date_assistance', '=', $date_start_db)
                    ->whereDate('date_assistance', '<', $new_date_start)->get();

                if (count($assistancesA) > 0) {
                    foreach ($assistancesA as $assistance) {
                        $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                            ->where('worker_id', $permitHour->worker_id)->get();

                        if (count($assistancesDetails) > 0) {
                            foreach ($assistancesDetails as $assistanceDetail) {
                                $assistanceDetail->status = 'A';
                                $assistanceDetail->update();
                            }
                        }
                    }
                }
            }

            $assistances = Assistance::whereDate('date_assistance', '=', $new_date_start)->get();

            if (count($assistances) > 0) {
                foreach ($assistances as $assistance) {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permitHour->worker_id)->get();

                    if (count($assistancesDetails) > 0) {
                        foreach ($assistancesDetails as $assistanceDetail) {
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
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso por horas modificado con éxito.'], 200);
    }


    public function destroy(PermitHourDestroyRequest $request)
    {
        DB::beginTransaction();
        try {
            $permitHour = PermitHour::find($request->get('permitHour_id'));


            $date_start = $permitHour->date_start;

            $permitHour->delete();
            DB::commit();

            $assistances = Assistance::whereDate('date_assistance', '=', $date_start)->get();

            if (count($assistances) > 0) {
                foreach ($assistances as $assistance) {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permitHour->worker_id)->get();

                    if (count($assistancesDetails) > 0) {
                        foreach ($assistancesDetails as $assistanceDetail) {
                            $assistanceDetail->status = 'A';
                            $assistanceDetail->update();
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso por hora eliminado con éxito.'], 200);
    }


    public function getAllPermits()
    {
        $permits_hours = PermitHour::select('id', 'date_start', 'hour', 'worker_id', 'created_at', 'reason')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($permits_hours)->toJson();

    }
}
