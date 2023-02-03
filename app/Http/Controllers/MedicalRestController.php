<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\MedicalRest;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class MedicalRestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('medicalRest.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('medicalRest.create', compact('workers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $medicalRest = MedicalRest::create([
                'reason' => $request->get('reason'),
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'date_end' => ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null,
                'worker_id' => $request->get('worker_id'),
            ]);

            if (!$request->file('file')) {
                $medicalRest->file = null;
                $medicalRest->save();

            } else {
                $path = public_path().'/images/medicalRest/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $medicalRest->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $medicalRest->file = $filename;
                    $medicalRest->save();
                } else {
                    $filename = 'pdf'.$medicalRest->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $medicalRest->file = $filename;
                    $medicalRest->save();
                }

            }

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$medicalRest->date_start)
                ->whereDate('date_assistance', '<=',$medicalRest->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $medicalRest->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        $workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'M';
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
        return response()->json(['message' => 'Descanso médico guardado con éxito.'], 200);

    }

    public function edit($medicalRest_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $medicalRest = MedicalRest::with('worker')->find($medicalRest_id);

        return view('medicalRest.edit', compact('medicalRest', 'workers'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $medicalRest = MedicalRest::find($request->get('medicalRest_id'));

            $medicalRest->reason = $request->get('reason');
            $medicalRest->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $medicalRest->date_end = ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null;
            $medicalRest->save();

            if (!$request->file('file')) {
                if ( $medicalRest->file == null )
                {
                    $medicalRest->file = null;
                    $medicalRest->save();
                }

            } else {
                // Primero eliminamos el pdf anterior
                if ( $medicalRest->file != null )
                {
                    $image_path = public_path().'/images/medicalRest/'.$medicalRest->file;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                // Ahora si guardamos el nuevo pdf
                $path = public_path().'/images/medicalRest/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $medicalRest->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $medicalRest->file = $filename;
                    $medicalRest->save();
                } else {
                    $filename = 'pdf'.$medicalRest->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $medicalRest->file = $filename;
                    $medicalRest->save();
                }

            }

            // TODO: Logica para verificar las fechas de las asistencias
            $assistances = Assistance::whereDate('date_assistance', '>=',$medicalRest->date_start)
                ->whereDate('date_assistance', '<=',$medicalRest->date_end)->get();

            if ( count($assistances) > 0 )
            {
                foreach ( $assistances as $assistance )
                {
                    $assistancesDetails = AssistanceDetail::where('assistance_id', $assistance->id)
                        ->where('worker_id', $medicalRest->worker_id)->get();

                    if ( count( $assistancesDetails ) > 0 )
                    {
                        $workingDay = WorkingDay::where('enable', 1)
                            ->orderBy('created_at', 'ASC')->first();
                        foreach ( $assistancesDetails as $assistanceDetail )
                        {
                            $assistanceDetail->hour_entry = $workingDay->time_start;
                            $assistanceDetail->hour_out = $workingDay->time_fin;
                            $assistanceDetail->status = 'M';
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
        return response()->json(['message' => 'Descanso médico modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $medicalRest = MedicalRest::find($request->get('medicalRest_id'));

            if ( $medicalRest->file != null )
            {
                $image_path = public_path().'/images/medicalRest/'.$medicalRest->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $medicalRest->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Descanso médico eliminado con éxito.'], 200);

    }

    public function getAllMedicalRest()
    {
        $medicalRests = MedicalRest::select('id', 'reason', 'date_start', 'date_end', 'file', 'worker_id', 'created_at')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($medicalRests)->toJson();

    }
}
