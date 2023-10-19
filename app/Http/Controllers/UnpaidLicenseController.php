<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnpaidLicenseRequest;
use App\Http\Requests\UpdateUnpaidLicenseRequest;
use App\UnpaidLicense;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class UnpaidLicenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('unpaidLicense.index', compact('permissions'));
    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();
        return view('unpaidLicense.create', compact('workers'));
    }

    public function store(StoreUnpaidLicenseRequest $request)
    {
        DB::beginTransaction();
        try {

            $unpaidLicense = UnpaidLicense::create([
                'reason' => $request->get('reason'),
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'date_end' => ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null,
                'worker_id' => $request->get('worker_id'),
            ]);

            if (!$request->file('file')) {
                $unpaidLicense->file = null;
                $unpaidLicense->save();

            } else {
                $path = public_path().'/images/unpaidLicense/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $unpaidLicense->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $unpaidLicense->file = $filename;
                    $unpaidLicense->save();
                } else {
                    $filename = 'pdf'.$unpaidLicense->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $unpaidLicense->file = $filename;
                    $unpaidLicense->save();
                }

            }

            // TODO: Logica para verificar las fechas de las asistencias

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Licencia sin gozo guardada con éxito.'], 200);

    }

    public function edit($unpaidLicense_id)
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $unpaidLicense = UnpaidLicense::with('worker')->find($unpaidLicense_id);

        return view('unpaidLicense.edit', compact('unpaidLicense', 'workers'));
    }

    public function update(UpdateUnpaidLicenseRequest $request)
    {
        DB::beginTransaction();
        try {

            $unpaidLicense = UnpaidLicense::find($request->get('unpaidLicense_id'));

            $unpaidLicense->reason = $request->get('reason');
            $unpaidLicense->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $unpaidLicense->date_end = ($request->get('date_end') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_end')) : null;
            $unpaidLicense->save();

            if (!$request->file('file')) {
                if ( $unpaidLicense->file == null )
                {
                    $unpaidLicense->file = null;
                    $unpaidLicense->save();
                }

            } else {
                if ( $unpaidLicense->file != null )
                {
                    $image_path = public_path().'/images/unpaidLicense/'.$unpaidLicense->file;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                $path = public_path().'/images/unpaidLicense/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $unpaidLicense->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $unpaidLicense->file = $filename;
                    $unpaidLicense->save();
                } else {
                    $filename = 'pdf'.$unpaidLicense->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $unpaidLicense->file = $filename;
                    $unpaidLicense->save();
                }

            }

            // TODO: Logica para verificar las fechas de las asistencias

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Licencia sin gozo modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $unpaidLicense = UnpaidLicense::find($request->get('unpaidLicense_id'));

            if ( $unpaidLicense->file != null )
            {
                $image_path = public_path().'/images/unpaidLicense/'.$unpaidLicense->file;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $unpaidLicense->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Licencia sin gozo eliminada con éxito.'], 200);

    }

    public function getAllUnpaidLicenses()
    {
        $unpaidLicenses = UnpaidLicense::select('id', 'date_start', 'date_end', 'file', 'worker_id', 'created_at', 'reason')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($unpaidLicenses)->toJson();

    }
}
