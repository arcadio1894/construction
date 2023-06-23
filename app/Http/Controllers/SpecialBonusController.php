<?php

namespace App\Http\Controllers;

use App\DateDimension;
use App\Exports\BonusesReportExcelExport;
use App\SpecialBonus;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpecialBonusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('bonus.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('bonus.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date'));
            $specialBonus = SpecialBonus::create([
                'reason' => $request->get('reason'),
                'date' => ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null,
                'amount' => ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount'),
                'worker_id' => $request->get('worker_id'),
                'week' => $fecha->week
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
        return response()->json(['message' => 'Bono especial guardado con éxito.'], 200);

    }

    public function edit($refund_id)
    {
        $specialBonus = SpecialBonus::with('worker')->find($refund_id);

        return view('bonus.edit', compact('specialBonus'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date'));

            $specialBonus = SpecialBonus::find($request->get('bonus_id'));

            $specialBonus->reason = $request->get('reason');
            $specialBonus->date = ($request->get('date') != null || $request->get('date') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date')) : null;
            $specialBonus->amount =  ($request->get('amount') == null || $request->get('amount') == '') ? 0: $request->get('amount');
            $specialBonus->week = $fecha->week;
            $specialBonus->save();

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
        return response()->json(['message' => 'Bono especial modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $specialBonus = SpecialBonus::find($request->get('bonus_id'));

            /*if ( $license->file != null )
            {
                $image_path = public_path().'/images/license/'.$license->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }*/

            $specialBonus->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Bono especial eliminado con éxito.'], 200);

    }

    public function getAllBonus()
    {
        $specialBonus = SpecialBonus::select('id', 'date', 'reason', 'amount', 'worker_id', 'created_at')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($specialBonus)->toJson();

    }

    public function report()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $workers = Worker::where('enable', true)
            ->get();

        $years = DateDimension::distinct()->get(['year']);

        $types = collect([
            [
                'id' => 1,
                'name' => 'Semanal'
            ],
            [
                'id' => 2,
                'name' => 'Mensual'
            ]
        ]);

        return view('bonus.report', compact( 'permissions', 'workers', 'years', 'types'));

    }

    public function reportBonuses()
    {
        $type = $_GET['type'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $week = $_GET['week'];
        $worker_id = $_GET['worker'];

        if ( $worker_id == 0 )
        {
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $specialBonus = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('week', $week)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                // Mensual
                $specialBonus = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            }
        } else {
            // El usuario eligio un trabajador
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $specialBonus = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('week', $week)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                // Mensual
                $specialBonus = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            }
        }

        return response()->json([
            'bonuses' => $specialBonus
        ], 200);
    }

    public function downloadBonuses()
    {
        $type = $_GET['type'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $week = $_GET['week'];
        $worker_id = $_GET['worker'];

        if ( $worker_id == 0 )
        {
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $specialBonuses = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('week', $week)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                // Mensual
                $specialBonuses = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            }
        } else {
            // El usuario eligio un trabajador
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $specialBonuses = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('week', $week)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                // Mensual
                $specialBonuses = SpecialBonus::with(['worker'])
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date', 'desc')
                    ->get();
            }
        }

        $dates = "BONOS ESPECIALES " . $year;
        $bonuses_array = [];

        foreach ( $specialBonuses as $bonus )
        {
            array_push($bonuses_array, [
                'trabajador' => $bonus->worker->first_name.' '.$bonus->worker->last_name,
                'fecha' => $bonus->date->format('d/m/Y'),
                'week' => 'SEMANA '.$bonus->week,
                'reason' => $bonus->reason,
                'total' => $bonus->amount,
            ]);
        }
        $nombre = "BONOS_ESPECIALES_" . $year;

        return (new BonusesReportExcelExport($bonuses_array, $dates))->download($nombre.'.xlsx');

    }
}
