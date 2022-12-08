<?php

namespace App\Http\Controllers;

use App\WorkFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkFunctionController extends Controller
{
    public function index()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('workFunction.index', compact('permissions'));
    }

    public function indexDeleted()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('workFunction.indexDeleted', compact('permissions'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $workFunction = WorkFunction::create([
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cargo guardado con éxito.'], 200);
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $workFunction = WorkFunction::find($request->get('workFunction_id'));

            $workFunction->description = $request->get('description');
            $workFunction->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cargo modificado con éxito.','url'=>route('workFunctions.index')], 200);
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $workFunction = WorkFunction::find($request->get('workFunction_id'));

            $workFunction->enable = false;

            $workFunction->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cargo inhabilitado con éxito.'], 200);
    }


    public function create()
    {
        return view('workFunction.create');
    }

    public function edit($id)
    {
        $workFunction = WorkFunction::find($id);
        return view('workFunction.edit', compact('workFunction'));
    }


    public function getAllWorkFunctions()
    {
        $workFunctions = WorkFunction::select('id', 'description', 'enable')
            ->where('enable', true)->get();
        return datatables($workFunctions)->toJson();

    }

    public function getWorkFunctionsDeleted()
    {
        $workFunctions = WorkFunction::select('id', 'description', 'enable')
            ->where('enable', false)->get();
        return datatables($workFunctions)->toJson();

    }

    public function restore(Request $request)
    {
        DB::beginTransaction();
        try {

            $workFunction = WorkFunction::find($request->get('workFunction_id'));

            $workFunction->enable = true;

            $workFunction->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cargo habilitado con éxito.'], 200);
    }
}
