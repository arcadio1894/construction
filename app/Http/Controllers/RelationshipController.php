<?php

namespace App\Http\Controllers;

use App\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelationshipController extends Controller
{
    public function index()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('relationship.index', compact('permissions'));
    }

    public function indexDeleted()
    {
        //$permissions = Permission::all();
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('relationship.indexDeleted', compact('permissions'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $relationship = Relationship::create([
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Parentesco guardado con éxito.'], 200);
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $relationship = Relationship::find($request->get('relationship_id'));

            $relationship->description = $request->get('description');
            $relationship->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Parentesco modificado con éxito.','url'=>route('relationship.index')], 200);
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $relationship = Relationship::find($request->get('relationship_id'));

            $relationship->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Parentesco eliminado con éxito.'], 200);
    }


    public function create()
    {
        return view('relationship.create');
    }

    public function edit($id)
    {
        $relationship = Relationship::find($id);
        return view('relationship.edit', compact('relationship'));
    }


    public function getAllRelationships()
    {
        $civilStatuses = Relationship::select('id', 'description')->get();
        return datatables($civilStatuses)->toJson();

    }


}
