<?php

namespace App\Http\Controllers;

use App\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderExecutionController extends Controller
{
    public function indexOrderExecution()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderExecution.index', compact('permissions'));

    }

    public function getAllOrderExecution()
    {
        $quotes = Quote::with('customer')
            ->where('raise_status', 1)
            ->where('state_active', 'open')
            ->whereNotIn('state', ['canceled', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($quotes)->toJson();
    }

    public function indexOrderExecutionFinished()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderExecution.finish', compact('permissions'));

    }

    public function getAllOrderExecutionFinished()
    {
        $quotes = Quote::with('customer')
            ->where('raise_status', 1)
            ->where('state_active', 'close')
            ->whereNotIn('state', ['canceled', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($quotes)->toJson();
    }
}
