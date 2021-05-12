<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Listar los clientes code here
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::create([
            'business_name' => $request->get('business_name'),
            'RUC' => $request->get('RUC'),
            'code' => $request->get('code'),
            'contact_name' => $request->get('contact_name'),
            'adress' => $request->get('adress'),
            'phone' => $request->get('phone'),
            'location' => $request->get('location'),
            'email' => $request->get('email'),

        ]);
        return response()->json(['message' => 'Cliente guardado con éxito.'], 200);
    }

    public function update(UpdateCustomerRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::find($request->get('customer_id'));

        $customer->business_name = $request->get('business_name');
        $customer->RUC = $request->get('RUC');
        $customer->code = $request->get('code');
        $customer->contact_name = $request->get('contact_name');
        $customer->adress = $request->get('adress');
        $customer->phone = $request->get('phone');
        $customer->location = $request->get('location');
        $customer->email = $request->get('email');
        $customer->save();

        return response()->json(['message' => 'Cliente modificado con éxito.'], 200);
    }

    public function destroy(DeleteCustomerRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::find($request->get('customer_id'));

        $user->delete();

        return response()->json(['message' => 'Cliente eliminado con éxito.'], 200);
    }

    public function create()
    {
        // NO se usará si utilizamos modals
        // Muestra un Formulario de creación
    }

    public function show(Customer $customer)
    {
        // Visualizar todos los datos de un cliente
    }

    public function edit(Customer $customer)
    {
        // NO se usará si utilizamos modals
        // Muestra un Formulario de edición
    }
}
