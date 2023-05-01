<?php

namespace App\Http\Controllers;

use App\Alimony;
use App\Worker;
use Illuminate\Http\Request;

class AlimonyController extends Controller
{
    public function index()
    {
        return view('alimony.index');
    }

    public function getWorkersAlimony()
    {
        $arrayWorkers = [];
        $alimonyWorkers = Worker::whereNotNull('pension')
            ->where('pension', '>', 0)->get();

        foreach ( $alimonyWorkers as $alimonyWorker )
        {
            $alimonies = Alimony::where('worker_id', $alimonyWorker->id)->get();

            array_push($arrayWorkers, [
                'worker_id' => $alimonyWorker->id,
                'worker_name' => $alimonyWorker->first_name.' '.$alimonyWorker->last_name,
                'numRegister' => count($alimonies),
                'workerFunction' => $alimonyWorker->work_function->description,
                'image' => $alimonyWorker->image
            ]);
        }

        return response()->json(['workers' => $arrayWorkers]);
    }

    public function create($worker_id)
    {
        $worker = Worker::find($worker_id);
        $alimony_pensions = Alimony::where('worker_id', $worker_id)
            ->orderBy('date', 'desc')->get();
        return view('alimony.create', compact('alimony_pensions', 'worker'));

    }
}
