<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePaymentDeadlineRequest;
use App\Http\Requests\StorePaymentDeadlineRequest;
use App\Http\Requests\UpdatePaymentDeadlineRequest;
use App\PaymentDeadline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentDeadlineController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('paymentDeadline.index', compact('permissions'));

    }

    public function create()
    {
        return view('paymentDeadline.create');
    }

    public function store(StorePaymentDeadlineRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $paymentDeadline = PaymentDeadline::create([
                'description' => $request->get('description'),
                'days' => $request->get('days'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Plazo de pago guardado con éxito.'], 200);

    }

    public function edit($id)
    {
        $paymentDeadline = PaymentDeadline::find($id);
        return view('paymentDeadline.edit', compact('paymentDeadline'));
    }

    public function update(UpdatePaymentDeadlineRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $paymentDeadline = PaymentDeadline::find($request->get('paymentDeadline_id'));

            $paymentDeadline->description = $request->get('description');
            $paymentDeadline->days = $request->get('days');
            $paymentDeadline->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Plazo de pago modificado con éxito.','url'=>route('paymentDeadline.index')], 200);

    }

    public function destroy(DeletePaymentDeadlineRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $paymentDeadline = PaymentDeadline::find($request->get('paymentDeadline_id'));

            $paymentDeadline->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Plazo de pago eliminado con éxito.'], 200);

    }

    public function getPaymentDeadlines()
    {
        $paymentDeadlines = PaymentDeadline::select('id', 'description', 'days')->get();
        return datatables($paymentDeadlines)->toJson();
    }
}
