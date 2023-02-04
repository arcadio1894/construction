<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\Permit;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermitController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('permit.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('permit.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $permit = Permit::create([
                'reason' => $request->get('reason'),
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'date_end' => ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null,
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

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$permit->date_start)
                ->whereDate('date_assistance', '<=',$permit->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permit->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        $workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'P';
                            $assistanceDetail->justification = null;
                            $assistanceDetail->obs_justification = null;
                            $assistanceDetail->working_day_id = $workingDay->id;
                            $assistanceDetail->save();
                        }
                    }
                }
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso guardado con éxito.'], 200);

    }

    public function edit($permit_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $permit = Permit::with('worker')->find($permit_id);

        return view('permit.edit', compact('permit', 'workers'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $permit = Permit::find($request->get('permit_id'));

            $permit->reason = $request->get('reason');
            $permit->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $permit->date_end = ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null;
            $permit->save();

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

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$permit->date_start)
                ->whereDate('date_assistance', '<=',$permit->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $permit->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        $workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'P';
                            $assistanceDetail->justification = null;
                            $assistanceDetail->obs_justification = null;
                            $assistanceDetail->working_day_id = $workingDay->id;
                            $assistanceDetail->save();
                        }
                    }
                }
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $permit = Permit::find($request->get('permit_id'));

            /*if ( $license->file != null )
            {
                $image_path = public_path().'/images/license/'.$license->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }*/

            $permit->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Permiso eliminado con éxito.'], 200);

    }

    public function getAllPermits()
    {
        $permits = Permit::select('id', 'date_start', 'date_end', 'worker_id', 'created_at', 'reason')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($permits)->toJson();

    }
}
