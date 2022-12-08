<?php

namespace App\Http\Controllers;

use App\CivilStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CivilStatusController extends Controller
{
    public function index()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('civilStatus.index', compact('permissions'));
    }

    public function indexDeleted()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('civilStatus.indexDeleted', compact('permissions'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $civilStatus = CivilStatus::create([
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Estado civil guardado con éxito.'], 200);
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $civilStatus = CivilStatus::find($request->get('civilStatus_id'));

            $civilStatus->description = $request->get('description');
            $civilStatus->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Estado civil modificado con éxito.','url'=>route('civilStatuses.index')], 200);
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $civilStatus = CivilStatus::find($request->get('civilStatus_id'));

            $civilStatus->enable = false;

            $civilStatus->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Estado civil inhabilitado con éxito.'], 200);
    }


    public function create()
    {
        return view('civilStatus.create');
    }

    public function edit($id)
    {
        $civilStatus = CivilStatus::find($id);
        return view('civilStatus.edit', compact('civilStatus'));
    }


    public function getAllCivilStatus()
    {
        $civilStatuses = CivilStatus::select('id', 'description', 'enable')
            ->where('enable', true)->get();
        return datatables($civilStatuses)->toJson();

    }

    public function getCivilStatusesDeleted()
    {
        $civilStatuses = CivilStatus::select('id', 'description', 'enable')
            ->where('enable', false)->get();
        return datatables($civilStatuses)->toJson();

    }

    public function restore(Request $request)
    {
        DB::beginTransaction();
        try {

            $civilStatus = CivilStatus::find($request->get('civilStatus_id'));

            $civilStatus->enable = true;

            $civilStatus->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Estado civil habilitado con éxito.'], 200);
    }
}
