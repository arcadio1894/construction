<?php

namespace App\Http\Controllers;

use App\FifthCategory;
use App\Http\Requests\FifthCategoryDeleteRequest;
use App\Http\Requests\FifthCategoryStoreRequest;
use App\Http\Requests\FifthCategoryUpdateRequest;
use App\Http\Requests\FifthCategoryWorkerStoreRequest;
use App\User;
use App\Work;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FifthCategoryController extends Controller
{
    public function index()
    {
        $workers = Worker::whereNull('five_category')->get();
        return view('fifthCategory.index', compact('workers'));
    }

    public function getWorkers()
    {
        $workers = Worker::whereNull('five_category')->get();
        return $workers;
    }

    public function getWorkersFifthCategory()
    {
        $arrayWorkers = [];
        $fifthWorkers = Worker::whereNotNull('five_category')->get();

        foreach ( $fifthWorkers as $fifthWorker )
        {
            $fifthCategories = FifthCategory::where('worker_id', $fifthWorker->id)->get();

            array_push($arrayWorkers, [
                'worker_id' => $fifthWorker->id,
                'worker_name' => $fifthWorker->first_name.' '.$fifthWorker->last_name,
                'numRegister' => count($fifthCategories),
                'workerFunction' => $fifthWorker->work_function->description,
                'image' => $fifthWorker->image
            ]);
        }

        return response()->json(['workers' => $arrayWorkers]);
    }

    public function storeWorkerFifthCategory( FifthCategoryWorkerStoreRequest $request )
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $worker = Worker::find($request->get('worker'));
            $worker->five_category = 1;
            $worker->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Trabajador agregado a renta de quinta categoría con éxito.'], 200);

    }

    public function destroyWorkerFifthCategory( FifthCategoryWorkerStoreRequest $request )
    {
        $validated = $request->validated();

        $fifthCategories = FifthCategory::where('worker_id', $request->get('worker'))
            ->get();
        if( count($fifthCategories) > 0 )
        {
            return response()->json(['message' => 'Este trabajador ya tiene registrado pagos, no se puede quitar.'], 422);

        }

        DB::beginTransaction();
        try {

            $worker = Worker::find($request->get('worker'));
            $worker->five_category = null;
            $worker->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Trabajador quitado de renta de quinta categoría con éxito.'], 200);

    }

    public function create($worker_id)
    {
        $worker = Worker::find($worker_id);
        $fifthCategories = FifthCategory::where('worker_id', $worker_id)
            ->orderBy('date', 'desc')->get();
        return view('fifthCategory.create', compact('fifthCategories', 'worker'));
    }

    public function store(FifthCategoryStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $fifthCategory = FifthCategory::create([
                'date' => ($request->get('date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null,
                'amount' => $request->get('amount'),
                'worker_id' => $request->get('worker_id'),

            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Pago guardado con éxito.'], 200);

    }

    public function update(FifthCategoryUpdateRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $fifthCategory = FifthCategory::find($request->get('fifthCategory_id'));
            $fifthCategory->date = ($request->get('date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null;
            $fifthCategory->amount = $request->get('amount');
            $fifthCategory->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Pago actualizado con éxito.'], 200);

    }

    public function destroy(FifthCategoryDeleteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $fifthCategory = FifthCategory::find($request->get('fifthCategory_id'));
            $fifthCategory->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Pago eliminado con éxito.'], 200);

    }
}
