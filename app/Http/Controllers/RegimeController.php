<?php

namespace App\Http\Controllers;

use App\Regime;
use App\RegimeDetail;
use App\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegimeController extends Controller
{
    const DAYS = [
        ['id'=>0,'day'=>'Domingo'],
        ['id'=>1,'day'=>'Lunes'],
        ['id'=>2,'day'=>'Martes'],
        ['id'=>3,'day'=>'Miércoles'],
        ['id'=>4,'day'=>'Jueves'],
        ['id'=>5,'day'=>'Viernes'],
        ['id'=>6,'day'=>'Sábado'],
    ];
    public function create()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $regimes = Regime::all();

        $workingDays = WorkingDay::all();

        return view('regime.create', compact('permissions', 'regimes', 'workingDays'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $regime = Regime::create([
                'name' => '',
                'description' => '',
                'active' => false,
            ]);

            // Creamos los detalles
            for ( $i = 0; $i<count($this::DAYS); $i++)
            {
                RegimeDetail::create([
                    'regime_id' => $regime->id,
                    'dayNumber' => $this::DAYS[$i]['id'],
                    'dayName' => $this::DAYS[$i]['day']
                ]);
            }


            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Régimen creada con éxito.', 'regime' => $regime], 200);

    }

    public function getWorkingsDayByRegime($id_regime)
    {
        $regime = Regime::with('details')->find($id_regime);

        return response()->json(['regime' => $regime], 200);
    }

    public function edit(Regime $regime)
    {
        //
    }

    public function update(Request $request, $id_regime)
    {
        DB::beginTransaction();
        try {

            $regime = Regime::find($id_regime);
            $regime->name = ( trim($request->get('name')) == '' ) ? null: $request->get('name');
            $regime->description = ( trim($request->get('description')) == '' ) ? null: $request->get('description');
            $regime->active = $request->get('active');
            $regime->save();

            $regime_send = Regime::find($regime->id);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Régimen de trabajo editada con éxito.', 'regime' => $regime_send], 200);

    }

    public function updateDetailsRegime(Request $request, $id_regime)
    {
        DB::beginTransaction();
        try {

            $regime = Regime::find($id_regime);

            $id_details = $request->get('detailIds');
            $day_nums = $request->get('dayNums');
            $workingDays = $request->get('workingDays');

            for ( $i=0; $i< sizeof($id_details); $i++ )
            {
                $regimeDetail = RegimeDetail::find($id_details[$i]);
                $regimeDetail->working_day_id = $workingDays[$i];
                $regimeDetail->save();
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Régimen de trabajo editada con éxito.'], 200);

    }

    public function destroy(Request $request, $id_regime)
    {
        DB::beginTransaction();
        try {

            $regime = Regime::find($id_regime);

            $regimeDetails = RegimeDetail::where('regime_id', $regime->id)->get();

            if ( isset($regimeDetails) )
            {
                foreach ( $regimeDetails as $regimeDetail )
                {
                    $regimeDetail->delete();
                }
            }

            $regime->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Régimen de trabajo eliminada. Los horarios asociados también se eliminarán.'], 200);

    }
}
