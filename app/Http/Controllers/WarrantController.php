<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteWarrantRequest;
use App\Http\Requests\StoreWarrantRequest;
use App\Http\Requests\UpdateWarrantRequest;
use App\Warrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarrantController extends Controller
{
    public function index()
    {
        $warrants = Warrant::all();
        //$permissions = Permission::all();

        return view('warrant.index', compact('warrants'));
    }

    public function store(StoreWarrantRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $warrant = Warrant::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cédula de material guardado con éxito.'], 200);
    }

    public function update(UpdateWarrantRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $warrant = Warrant::find($request->get('warrant_id'));

            $warrant->name = $request->get('name');
            $warrant->description = $request->get('description');
            $warrant->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cédula de material modificada con éxito.','url'=>route('warrant.index')], 200);
    }

    public function destroy(DeleteWarrantRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $warrant = Warrant::find($request->get('warrant_id'));

            $warrant->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cédula de material eliminada con éxito.'], 200);
    }

    public function create()
    {
        return view('warrant.create');
    }

    public function edit($id)
    {
        $warrant = Warrant::find($id);
        return view('warrant.edit', compact('warrant'));
    }


    public function getWarrants()
    {
        $warrants = Warrant::select('id', 'name', 'description') -> get();
        return datatables($warrants)->toJson();
        //dd(datatables($customers)->toJson());
    }
}
