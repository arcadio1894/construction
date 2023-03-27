<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\Vacation;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('vacation.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('vacation.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $vacation = Vacation::create([
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'date_end' => ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null,
                'worker_id' => $request->get('worker_id'),
            ]);

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$vacation->date_start)
                ->whereDate('date_assistance', '<=',$vacation->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $vacation->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        /*$workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();*/
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'V';
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
        return response()->json(['message' => 'Vacaciones guardadas con éxito.'], 200);

    }

    public function edit($vacation_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $vacation = Vacation::with('worker')->find($vacation_id);

        return view('vacation.edit', compact('vacation', 'workers'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $vacation = Vacation::find($request->get('vacation_id'));

            $vacation->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $vacation->date_end = ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null;
            $vacation->save();

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$vacation->date_start)
                ->whereDate('date_assistance', '<=',$vacation->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $vacation->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        /*$workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();*/
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $workingDay = WorkingDay::find($assistanceDetail->working_day_id);
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'V';
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
        return response()->json(['message' => 'Vacaciones modificadas con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $vacation = Vacation::find($request->get('vacation_id'));

            $vacation->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Vacaciones eliminadas con éxito.'], 200);

    }

    public function getAllVacation()
    {
        $vacations = Vacation::select('id', 'date_start', 'date_end', 'worker_id', 'created_at')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($vacations)->toJson();

    }
}
