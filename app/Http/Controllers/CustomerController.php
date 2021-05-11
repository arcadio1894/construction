<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Listar los clientes
    }

    public function store(StoreCustomerRequest $request)
    {
        //
    }

    public function update(UpdateCustomerRequest $request)
    {
        //
    }

    public function destroy(DeleteCustomerRequest $request)
    {
        //
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
