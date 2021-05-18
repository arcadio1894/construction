<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        //$permissions = Permission::all();

        return view('customer.index', compact('customers'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

                $customer = Customer::create([
                    'business_name' => $request->get('business_name'),
                    'RUC' => $request->get('ruc'),
                    //'code' => $request->get('code'),
                    'contact_name' => $request->get('contact_name'),
                    'adress' => $request->get('adress'),
                    'phone' => $request->get('phone'),
                    'location' => $request->get('location'),
                    'email' => $request->get('email'),
                ]);

                $length = 5;
                $string = $customer->id;
                $codecustomer = 'C-'.str_pad($string,$length,"0", STR_PAD_LEFT);
                //output: 0012345

                $customer->code = $codecustomer;
                $customer->save();

                DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cliente guardado con éxito.'], 200);
    }

    public function update(UpdateCustomerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $customer = Customer::find($request->get('customer_id'));

            $customer->business_name = $request->get('business_name');
            $customer->RUC = $request->get('ruc');
            $customer->contact_name = $request->get('contact_name');
            $customer->adress = $request->get('adress');
            $customer->phone = $request->get('phone');
            $customer->location = $request->get('location');
            $customer->email = $request->get('email');
            $customer->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cliente modificado con éxito.','url'=>route('customer.index')], 200);
    }

    public function destroy(DeleteCustomerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            
            $customer = Customer::find($request->get('customer_id'));

            $customer->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cliente eliminado con éxito.'], 200);
    }

    public function create()
    {
        return view('customer.create');

    }

    public function show(Customer $customer)
    {
        // Visualizar todos los datos de un cliente
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customer.edit', compact('customer'));
    }   


    public function getCustomers()
    {
        $customers = Customer::select('id', 'code', 'business_name', 'RUC', 'contact_name', 'phone', 'adress', 'location') -> get();
        return datatables($customers)->toJson();
        //dd(datatables($customers)->toJson());
    }
}
