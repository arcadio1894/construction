<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Worker;
use App\WorkerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkerAccountController extends Controller
{
    public function index($worker_id)
    {
        //$permissions = Permission::all();
        $worker = Worker::find($worker_id);
        $banks = Bank::all();
        $user = Auth::user();
        $accounts = WorkerAccount::all();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('workerAccount.index', compact('permissions', 'worker', 'banks', 'accounts'));

    }

    public function store(Request $request, $worker_id)
    {
        DB::beginTransaction();
        try {

            $workerAccount = WorkerAccount::create([
                'worker_id' => $worker_id,
                'number_account' => null,
                'currency' => 'PEN',
                'bank_id' => 1,
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json([
            'message' => 'Cuenta Bancaria generada con éxito.',
            'account' => $workerAccount
        ], 200);

    }

    public function update(Request $request, $account_id)
    {
        $number_account = $request->input('number_account');
        $bank_id = $request->input('bank_id');
        $currency = $request->input('currency');

        DB::beginTransaction();
        try {

            $account = WorkerAccount::find($account_id);

            $account->number_account = $number_account;
            $account->bank_id = $bank_id;
            $account->currency = $currency;
            $account->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json([
            'message' => 'Cuenta Bancaria modificada con éxito.'
        ], 200);
    }

    public function destroy(Request $request, $account_id)
    {
        DB::beginTransaction();
        try {

            $account = WorkerAccount::find($account_id);

            $account->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json([
            'message' => 'Cuenta Bancaria eliminada con éxito.'
        ], 200);
    }
}
