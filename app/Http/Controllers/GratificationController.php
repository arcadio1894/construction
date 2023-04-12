<?php

namespace App\Http\Controllers;

use App\Gratification;
use App\GratiPeriod;
use App\Http\Requests\GratificationDeleteRequest;
use App\Http\Requests\GratificationPeriodDeleteRequest;
use App\Http\Requests\GratificationPeriodStoreRequest;
use App\Http\Requests\GratificationStoreRequest;
use App\Http\Requests\GratificationUpdateRequest;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GratificationController extends Controller
{

    public function index()
    {
        return view('gratification.index');
    }

    public function create($period_id)
    {
        $period = GratiPeriod::find($period_id);
        $period2 = GratiPeriod::with('gratifications')->find($period_id);

        $workers_id_registered =[];
        $gratifications = [];

        foreach ( $period2->gratifications as $gratification )
        {
            array_push($gratifications, [
                'worker_id' => $gratification->worker_id,
                'worker_name' => $gratification->worker->first_name . ' ' . $gratification->worker->last_name,
                'period' => $period2->description,
                'date' => $gratification->date->format('d/m/Y'),
                'amount' => $gratification->amount,
                'period_id' => $period2->id,
                'gratification_id' => $gratification->id,

            ]);
            array_push($workers_id_registered, $gratification->worker_id);
        }

        $workersNotRegisterd = Worker::where('id', '<>', 1)
            ->whereNotIn('id', $workers_id_registered)
            ->get();
        return view('gratification.create', compact('period', 'gratifications', 'workersNotRegisterd'));

    }

    public function getAllPeriodGratifications()
    {
        $periods = GratiPeriod::with('gratifications')->get();

        $numWorkers = Worker::all()->count()-1;

        return response()->json([
            'periods' => $periods,
            'numWorkers' => $numWorkers
        ], 200);
    }

    public function getAllGratificationsByPeriod( $period_id )
    {
        $period = GratiPeriod::find($period_id);
        $period2 = GratiPeriod::with('gratifications')->find($period_id);

        $workers_id_registered =[];
        $array_gratifications = [];

        foreach ( $period2->gratifications as $gratification )
        {
            array_push($array_gratifications, [
                'worker_id' => $gratification->worker_id,
                'worker_name' => $gratification->worker->first_name . ' ' . $gratification->worker->last_name,
                'period' => $period2->description,
                'date' => $gratification->date,
                'amount' => $gratification->amount,
                'period_id' => $period2->id,
                'gratification_id' => $gratification->id,

            ]);
            array_push($workers_id_registered, $gratification->worker_id);
        }

        $workersNotRegisterd = Worker::where('id', '<>', 1)
            ->whereNotIn('id', $workers_id_registered)
            ->get();

        return response()->json([
            'period' => $period,
            'gratifications' => $array_gratifications,
            'workersNotRegistered' => $workersNotRegisterd
        ], 200);
    }

    public function store(GratificationStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $gratification = Gratification::create([
                'reason' => $request->get('period_name'),
                'date' => ($request->get('date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null,
                'amount' => $request->get('amount'),
                'worker_id' => $request->get('worker_id'),
                'grati_period_id' => $request->get('period_id')
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación guardado con éxito.'], 200);

    }

    public function storePeriod(GratificationPeriodStoreRequest $request)
    {
        $validated = $request->validated();

        $gratiPeriod = GratiPeriod::where('month', $request->get('month'))
            ->where('year', $request->get('year'))->first();

        if ( isset($gratiPeriod) )
        {
            return response()->json(['message' => 'Ya hay un periodo en el mes y año especificado'], 422);
        }

        DB::beginTransaction();
        try {

            $description = '';
            if ($request->get('month') == 7)
            {
                $description = 'GRATI_JUL'.$request->get('year');
            } elseif ($request->get('month') == 12) {
                $description = 'GRATI_DIC'.$request->get('year');
            }

            $period = GratiPeriod::create([
                'description' => $description,
                'month' => $request->get('month'),
                'year' => $request->get('year')
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación de periodo guardado con éxito.'], 200);

    }

    public function updatePeriod(GratificationPeriodStoreRequest $request)
    {
        $validated = $request->validated();

        $gratiPeriod = GratiPeriod::where('month', $request->get('month'))
            ->where('year', $request->get('year'))
            ->where('id', '<>', $request->get('period_id'))
            ->first();

        if ( isset($gratiPeriod) )
        {
            return response()->json(['message' => 'Ya hay un periodo en el mes y año especificado'], 422);
        }

        DB::beginTransaction();
        try {

            $description = '';
            if ($request->get('month') == 7)
            {
                $description = 'GRATI_JUL'.$request->get('year');
            } elseif ($request->get('month') == 12) {
                $description = 'GRATI_DIC'.$request->get('year');
            }

            $period = GratiPeriod::find($request->get('period_id'));
            $period->description = $description;
            $period->month = $request->get('month');
            $period->year = $request->get('year');
            $period->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación de periodo actualizado con éxito.'], 200);

    }

    public function destroyPeriod(GratificationPeriodDeleteRequest $request)
    {
        $validated = $request->validated();

        $gratifications = Gratification::where('grati_period_id', $request->get('period_id'))
            ->get();

        if ( count($gratifications) > 0 )
        {
            return response()->json(['message' => 'Ya hay gratificaciones registradas, no se puede eliminar.'], 422);

        }

        DB::beginTransaction();
        try {

            $period = GratiPeriod::find($request->get('period_id'));

            $period->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación de periodo eliminado con éxito.'], 200);

    }

    public function update(GratificationUpdateRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $gratification = Gratification::find($request->get('gratification_id'));
            $gratification->date = ($request->get('date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null;
            $gratification->amount = $request->get('amount');
            $gratification->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación actualizada con éxito.'], 200);

    }

    public function destroy(GratificationDeleteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $gratification = Gratification::find($request->get('gratification_id'));
            $gratification->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gratificación eliminada con éxito.'], 200);

    }
}
