<?php

namespace App\Http\Controllers;

use App\AssistanceDetail;
use App\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkingDayController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $workingDays = WorkingDay::all();

        return view('workingDay.create', compact('permissions', 'workingDays'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $workingDay = WorkingDay::create([
                'enable' => true,
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Jornada creada con éxito.', 'workingDay' => $workingDay], 200);

    }

    public function show(WorkingDay $workingDay)
    {
        //
    }

    public function edit(WorkingDay $workingDay)
    {
        //
    }

    public function update(Request $request, $id_workingDay)
    {
        DB::beginTransaction();
        try {

            $workingDay = WorkingDay::find($id_workingDay);
            $workingDay->description = ( trim($request->get('description')) == '' ) ? null: $request->get('description');
            $workingDay->time_start = ($request->get('time_start') == '')? null: $request->get('time_start');
            $workingDay->time_fin = ($request->get('time_fin') == '')? null: $request->get('time_fin');
            $workingDay->enable = $request->get('enable');
            $workingDay->save();

            $workingDay_send = WorkingDay::find($workingDay->id);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Jornada de trabajo editada con éxito.', 'workingDay' => $workingDay_send], 200);

    }

    public function destroy(Request $request, $id_workingDay)
    {
        DB::beginTransaction();
        try {

            $workingDay = WorkingDay::find($id_workingDay);

            $assistancesDetails = AssistanceDetail::where('working_day_id', $workingDay->id)->get();

            if ( isset($assistances) )
            {
                foreach ( $assistancesDetails as $assistancesDetail )
                {
                    $assistancesDetail->working_day_id = null;
                    $assistancesDetail->save();
                }
            }

            $workingDay->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Jornada de trabajo eliminada. Las asistencias relacionadas se han modificado también.. '], 200);

    }
}
