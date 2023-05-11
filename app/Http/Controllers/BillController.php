<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('bill.index', compact('permissions'));

    }

    public function create()
    {
        return view('bill.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $bill = Bill::create([
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Tipo de gasto guardado con éxito.'], 200);

    }

    public function edit($bill_id)
    {
        $bill = Bill::find($bill_id);

        return view('bill.edit', compact('bill'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $bill = Bill::find($request->get('bill_id'));

            $bill->description = $request->get('description');
            $bill->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Tipo de gasto modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        $expenses = Expense::where('bill_id', $request->get('bill_id'))->get();

        if ( count($expenses) > 0 )
        {
            return response()->json(['message' => 'No se puede eliminar porque hay gastos con este tipo'], 422);
        }

        DB::beginTransaction();
        try {

            $bill = Bill::find($request->get('bill_id'));

            $bill->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Tipo de gasto eliminado con éxito.'], 200);

    }

    public function getAllBills()
    {
        $bill = Bill::select('id', 'description')
            ->get();
        return datatables($bill)->toJson();

    }
}
