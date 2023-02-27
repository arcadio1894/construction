<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('discount.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('discount.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $discount = Discount::create([
                'reason' => $request->get('reason'),
                'date' => ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null,
                'amount' => ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount'),
                'worker_id' => $request->get('worker_id'),
            ]);

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
        return response()->json(['message' => 'Descuento guardado con éxito.'], 200);

    }

    public function edit($discount_id)
    {
        $discount = Discount::with('worker')->find($discount_id);

        return view('discount.edit', compact('discount'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $discount = Discount::find($request->get('permit_id'));

            $discount->reason = $request->get('reason');
            $discount->date = ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null;
            $discount->amount =  ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount');
            $discount->save();

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
        return response()->json(['message' => 'Descuento modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $discount = Discount::find($request->get('discount_id'));

            /*if ( $license->file != null )
            {
                $image_path = public_path().'/images/license/'.$license->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }*/

            $discount->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Descuento eliminado con éxito.'], 200);

    }

    public function getAllPermits()
    {
        $discounts = Discount::select('id', 'date', 'reason', 'amount', 'worker_id', 'created_at')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($discounts)->toJson();

    }
}
