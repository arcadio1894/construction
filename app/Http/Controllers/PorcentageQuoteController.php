<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePorcentageQuoteRequest;
use App\Http\Requests\StorePorcentageQuoteRequest;
use App\Http\Requests\UpdatePorcentageQuoteRequest;
use App\PorcentageQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PorcentageQuoteController extends Controller
{
    public function index()
    {
        $porcentages = PorcentageQuote::all();
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('porcentagesQuote.index', compact('porcentages', 'permissions'));
    }


    public function store(StorePorcentageQuoteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $porcentageQuote = PorcentageQuote::create([
                'name' => $request->get('name'),
                'value' => $request->get('value'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Porcentaje de cotización guardado con éxito.'], 200);
    }


    public function update(UpdatePorcentageQuoteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $porcentageQuote = PorcentageQuote::find($request->get('porcentage_id'));

            if ( !in_array( $porcentageQuote->name,  ['utility', 'rent', 'letter', 'igv'] ))
            {
                $porcentageQuote->name = $request->get('name');
            }

            $porcentageQuote->value = $request->get('value');
            $porcentageQuote->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Porcentajes de cotización modificado con éxito.','url'=>route('porcentageQuote.index')], 200);
    }


    public function destroy(DeletePorcentageQuoteRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $porcentageQuote = PorcentageQuote::find($request->get('porcentage_id'));

            $porcentageQuote->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Porcentaje de cotización eliminado con éxito.'], 200);
    }


    public function create()
    {
        return view('porcentagesQuote.create');
    }

    public function edit($id)
    {
        $porcentageQuote = PorcentageQuote::find($id);
        return view('porcentagesQuote.edit', compact('porcentageQuote'));
    }


    public function getPorcentageQuotes()
    {
        $porcentageQuotes = PorcentageQuote::select('id', 'name', 'value') -> get();
        return datatables($porcentageQuotes)->toJson();
        //dd(datatables($customers)->toJson());
    }
}
