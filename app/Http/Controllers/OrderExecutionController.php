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
            ->whereNotIn('state', ['canceled', 'expired'])
            ->get();
        return datatables($quotes)->toJson();
    }
}
