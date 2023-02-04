<?php

namespace App\Http\Controllers;

use App\ReasonSuspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReasonSuspensionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('reasonSuspension.index', compact('permissions'));

    }

    public function create()
    {
        return view('reasonSuspension.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $reason = ReasonSuspension::create([
                'reason' => $request->get('reason'),
                'days' => $request->get('days'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Razón de suspensión guardado con éxito.'], 200);

    }

    public function edit($permit_id)
    {
        $reason = ReasonSuspension::find($permit_id);

        return view('reasonSuspension.edit', compact('reason'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $reason = ReasonSuspension::find($request->get('reason_id'));

            $reason->reason = $request->get('reason');
            $reason->days = $request->get('days');
            $reason->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Razón de suspensión modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $reason = ReasonSuspension::find($request->get('reason_id'));

            $reason->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Razón de suspensión eliminado con éxito.'], 200);

    }

    public function getAllReasonSuspensions()
    {
        $reasons = ReasonSuspension::select('id', 'reason', 'days')
            ->with('worker')
            ->get();
        return datatables($reasons)->toJson();

    }
}
