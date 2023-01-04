<?php

namespace App\Http\Controllers;

use App\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ContractController extends Controller
{
    public function index()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('contract.index', compact('permissions'));
    }

    public function indexDeleted()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('contract.indexDeleted', compact('permissions'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $contract = Contract::create([
                'code' => $request->get('code'),
                'date_start' => ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null,
                'date_fin' => ($request->get('date_fin') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_fin')) : null,
            ]);

            if (!$request->file('file')) {
                $contract->file = null;
                $contract->save();

            } else {
                $path = public_path().'/images/contracts/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $contract->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $contract->file = $filename;
                    $contract->save();
                } else {
                    $filename = 'pdf'.$contract->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $contract->file = $filename;
                    $contract->save();
                }

            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Contrato guardado con éxito.'], 200);
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $contract = Contract::find($request->get('contract_id'));

            $contract->code = $request->get('code');
            $contract->date_start = ($request->get('date_start') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_start')) : null;
            $contract->date_fin = ($request->get('date_fin') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_fin')) : null;
            $contract->save();

            if (!$request->file('file')) {
                if ( $contract->file == null )
                {
                    $contract->file = null;
                    $contract->save();
                }

            } else {
                // Primero eliminamos el pdf anterior
                if ( $contract->file != null )
                {
                    $image_path = public_path().'/images/contracts/'.$contract->file;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                // Ahora si guardamos el nuevo pdf
                $path = public_path().'/images/contracts/';
                $image = $request->file('file');
                $extension = $request->file('file')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $contract->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $contract->file = $filename;
                    $contract->save();
                } else {
                    $filename = 'pdf'.$contract->id . '.' .$extension;
                    $request->file('file')->move($path, $filename);
                    $contract->file = $filename;
                    $contract->save();
                }

            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Contrato modificado con éxito.','url'=>route('contract.index')], 200);
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $contract = Contract::find($request->get('contract_id'));

            $contract->enable = false;

            $contract->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Contrato inhabilitado con éxito.'], 200);
    }


    public function create()
    {
        return view('contract.create');
    }

    public function edit($id)
    {
        $contract = Contract::find($id);
        return view('contract.edit', compact('contract'));
    }


    public function getAllContracts()
    {
        $contracts = Contract::select('id', 'code', 'date_start', 'date_fin', 'file', 'enable')
            ->where('enable', true)->get();
        return datatables($contracts)->toJson();

    }

    public function getContractsDeleted()
    {
        $contracts = Contract::select('id', 'code', 'date_start', 'date_fin', 'file', 'enable')
            ->where('enable', false)->get();
        return datatables($contracts)->toJson();

    }

    public function restore(Request $request)
    {
        DB::beginTransaction();
        try {

            $contract = Contract::find($request->get('contract_id'));

            $contract->enable = true;

            $contract->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Contrato habilitado con éxito.'], 200);
    }
}