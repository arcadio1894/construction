<?php

namespace App\Http\Controllers;

use App\PensionSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PensionSystemController extends Controller
{
    public function index()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('pensionSystem.index', compact('permissions'));
    }

    public function indexDeleted()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('pensionSystem.indexDeleted', compact('permissions'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $pensionSystem = PensionSystem::create([
                'description' => $request->get('description'),
                'percentage' => $request->get('percentage'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sistema de pensión con éxito.'], 200);
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $pensionSystem = PensionSystem::find($request->get('pensionSystem_id'));

            $pensionSystem->description = $request->get('description');
            $pensionSystem->percentage = $request->get('percentage');
            $pensionSystem->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sistema de pensión con éxito.','url'=>route('pensionSystems.index')], 200);
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $pensionSystem = PensionSystem::find($request->get('pensionSystem_id'));

            $pensionSystem->enable = false;

            $pensionSystem->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sistema de pensión inhabilitado con éxito.'], 200);
    }


    public function create()
    {
        return view('pensionSystem.create');
    }

    public function edit($id)
    {
        $pensionSystem = PensionSystem::find($id);
        return view('pensionSystem.edit', compact('pensionSystem'));
    }


    public function getAllPensionSystems()
    {
        $pensionSystems = PensionSystem::select('id', 'description', 'percentage', 'enable')
            ->where('enable', true)->get();
        return datatables($pensionSystems)->toJson();

    }

    public function getPensionSystemsDeleted()
    {
        $pensionSystems = PensionSystem::select('id', 'description', 'percentage', 'enable')
            ->where('enable', false)->get();
        return datatables($pensionSystems)->toJson();

    }

    public function restore(Request $request)
    {
        DB::beginTransaction();
        try {

            $pensionSystem = PensionSystem::find($request->get('pensionSystem_id'));

            $pensionSystem->enable = true;

            $pensionSystem->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Sistema de pensión habilitado con éxito.'], 200);
    }
}
