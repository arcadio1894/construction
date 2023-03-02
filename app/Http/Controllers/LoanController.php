<?php

namespace App\Http\Controllers;

use App\Due;
use App\Loan;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('loan.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('loan.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $loan = Loan::create([
                'reason' => $request->get('reason'),
                'date' => ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null,
                'num_dues' => ($request->get('num_dues') == '' || $request->get('num_dues') == 0) ? 1: $request->get('num_dues'),
                'amount_total'=> ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount'),
                'time_pay' => ($request->get('time_pay') == 0 || $request->get('time_pay') == '') ? 0: $request->get('time_pay'),
                'rate' => ($request->get('rate') == null || $request->get('rate') == '') ? 0: $request->get('rate'),
                'worker_id' => $request->get('worker_id'),
            ]);

            $date_start = Carbon::createFromFormat('d/m/Y', $request->get('date'));

            for ( $i = 1; $i<=$loan->num_dues; $i++ )
            {
                $due = Due::create([
                    'loan_id' => $loan->id,
                    'date' => $date_start->addDays($loan->time_pay),
                    'num_due' => $i,
                    'amount' => round( (float) (($loan->amount_total/$loan->num_dues)*(1+$loan->rate/100)), 2),
                    'worker_id' => $loan->worker_id,
                ]);
            }

            /*if (!$request->file('file')) {
                $license->file = null;
                $license->save();

            } else {
                $path = public_path().'/images/license/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $license->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $license->file = $filename;
                    $license->save();
                } else {
                    $filename = 'pdf'.$license->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $license->file = $filename;
                    $license->save();
                }

            }*/

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Préstamo guardado con éxito.'], 200);

    }

    public function edit($loan_id)
    {
        $loan = Loan::with('worker')->find($loan_id);

        return view('loan.edit', compact('loan'));

    }

    public function update(Request $request)
    {

        DB::beginTransaction();
        try {

            $loan = Loan::find($request->get('loan_id'));

            $loan->reason = $request->get('reason');
            $loan->date = ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null;
            $loan->amount_total =  ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount');
            $loan->num_dues = ($request->get('num_dues') == '' || $request->get('num_dues') == 0) ? 1: $request->get('num_dues');
            $loan->time_pay = ($request->get('time_pay') == 0 || $request->get('time_pay') == '') ? 0: $request->get('time_pay');
            $loan->rate = ($request->get('rate') == null || $request->get('rate') == '') ? 0: $request->get('rate');
            $loan->save();

            $dues = Due::where('loan_id',$loan->id)->get();
            foreach ( $dues as $due )
            {
                $due->delete();
            }

            $date_start = Carbon::createFromFormat('d/m/Y', $request->get('date'));
            $amount_total =  ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount');
            $num_dues = ($request->get('num_dues') == '' || $request->get('num_dues') == null) ? 0: $request->get('num_dues');
            $time_pay = ($request->get('time_pay') == null || $request->get('time_pay') == '') ? 0: $request->get('time_pay');
            $rate = ($request->get('rate') == null || $request->get('rate') == '') ? 0: $request->get('rate');


            for ( $i = 1; $i<=$num_dues; $i++ )
            {
                $due = Due::create([
                    'loan_id' => $loan->id,
                    'date' => $date_start->addDays($time_pay),
                    'num_due' => $i,
                    'amount' => round( (float) (($amount_total/$loan->num_dues)*(1+$rate/100)), 2),
                    'worker_id' => $loan->worker_id,
                ]);
            }

            /*if (!$request->file('file')) {
                if ( $license->file == null )
                {
                    $license->file = null;
                    $license->save();
                }

            } else {
                // Primero eliminamos el pdf anterior
                if ( $license->file != null )
                {
                    $image_path = public_path().'/images/license/'.$license->file;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                // Ahora si guardamos el nuevo pdf
                $path = public_path().'/images/license/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $license->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $license->file = $filename;
                    $license->save();
                } else {
                    $filename = 'pdf'.$license->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $license->file = $filename;
                    $license->save();
                }

            }*/

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Préstamo modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $loan = Loan::find($request->get('loan_id'));

            /*if ( $license->file != null )
            {
                $image_path = public_path().'/images/license/'.$license->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }*/
            $dues = Due::where('loan_id',$loan->id)->get();
            foreach ( $dues as $due )
            {
                $due->delete();
            }

            $loan->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Préstamo eliminado con éxito.'], 200);

    }

    public function getAllLoan()
    {
        $loans = Loan::select(
            'id',
            'reason',
            'date',
            'num_dues',
            'amount_total',
            'time_pay',
            'rate',
            'worker_id',
            'created_at')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($loans)->toJson();

    }

    public function getAllDuesLoan( $loan_id )
    {
        $dues = Due::where('loan_id', $loan_id)->get();

        $arrayDues = [];

        foreach ( $dues as $due )
        {
            array_push($arrayDues,
                [
                    'num_due'=> $due->num_due,
                    'date' => $due->date,
                    'amount' => $due->amount,
                ]);
        }

        return response()->json(['dues' => $arrayDues], 200);

    }
}
