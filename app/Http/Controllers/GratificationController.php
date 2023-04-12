<?php

namespace App\Http\Controllers;

use App\Gratification;
use App\GratiPeriod;
use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    }

    public function show(Gratification $gratification)
    {
        //
    }

    public function edit(Gratification $gratification)
    {
        //
    }

    public function update(Request $request, Gratification $gratification)
    {
        //
    }

    public function destroy(Gratification $gratification)
    {
        //
    }
}
