<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSupplierRequest;
use App\Http\Requests\RestoreSupplierRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('supplier.index', compact('permissions'));
    }

    public function create()
    {
        return view('supplier.create');

    }

    public function store(StoreSupplierRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            if ( ($request->get('special') !== 'true') && strlen($request->get('ruc')) > 11 )
            {
                return response()->json(['message' => 'El RUC es demasiado largo, porque no es extranjero'], 422);
            }

            $supplier = Supplier::create([
                'business_name' => $request->get('business_name'),
                'RUC' => $request->get('ruc'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'special' => ($request->get('special') === 'true') ? true:false,

            ]);

            $length = 5;
            $string = $supplier->id;
            $codecustomer = 'PROV-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            //output: 0012345

            $supplier->code = $codecustomer;
            $supplier->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Proveedor guardado con éxito.'], 200);
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            if ( ($request->get('special') !== 'true') && strlen($request->get('ruc')) > 11 )
            {
                return response()->json(['message' => 'El RUC es demasiado largo, porque no es extranjero'], 422);
            }

            $supplier = Supplier::find($request->get('supplier_id'));

            $supplier->business_name = $request->get('business_name');
            $supplier->RUC = $request->get('ruc');
            $supplier->address = $request->get('address');
            $supplier->phone = $request->get('phone');
            $supplier->email = $request->get('email');
            $supplier->special = ($request->get('special') === 'true') ? true:false;
            $supplier->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Proveedor modificado con éxito.','url'=>route('supplier.index')], 200);
    }

    public function destroy(DeleteSupplierRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $supplier = Supplier::find($request->get('supplier_id'));

            $supplier->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Proveedor eliminado con éxito.'], 200);
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::select('id', 'code', 'business_name', 'RUC', 'address', 'phone', 'email') ->with('accounts') -> get();
        return datatables($suppliers)->toJson();
        //dd(datatables($customers)->toJson());
    }

    public function indexrestore()
    {
        return view('supplier.restore');
    }

    public function getSuppliersDestroy()
    {
        $suppliers = Supplier::onlyTrashed()->get();
        return datatables($suppliers)->toJson();
        //dd(datatables($customers)->toJson());
    }

    public function restore(RestoreSupplierRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $supplier = Supplier::onlyTrashed()->where('id', $request->get('supplier_id'))->first();
            $supplier->restore();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Proveedor restaurado con éxito.'], 200);
    }
}
