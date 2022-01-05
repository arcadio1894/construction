<?php

namespace App\Http\Controllers;

use App\OrderService;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{
    public function index()
    {
        //
    }

    public function createOrderServices()
    {
        $suppliers = Supplier::all();
        $users = User::all();

        $maxId = OrderService::max('id')+1;
        $length = 5;
        $codeOrder = 'OS-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        return view('orderService.createOrderService', compact('users', 'codeOrder', 'suppliers'));

    }

    public function store(Request $request)
    {
        //
    }

    public function show(OrderService $orderService)
    {
        //
    }

    public function edit(OrderService $orderService)
    {
        //
    }

    public function update(Request $request, OrderService $orderService)
    {
        //
    }

    public function destroy(OrderService $orderService)
    {
        //
    }
}
