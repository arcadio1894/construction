<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Entry;
use App\SupplierCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierCreditController extends Controller
{
    public function indexCredits()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('credit.indexSupplierCredit', compact('permissions'));
    }

    public function getOnlyInvoicesPurchase()
    {
        $entries = Entry::with('supplier')
            ->with('credit')
            ->where('type_order', 'purchase')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($entries)->toJson();
    }

    public function getOnlyCreditsSupplier()
    {
        $credits = SupplierCredit::with('supplier')
            ->with('entry')
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables($credits)->toJson();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Credit $credit)
    {
        //
    }

    public function edit(Credit $credit)
    {
        //
    }

    public function update(Request $request, Credit $credit)
    {
        //
    }

    public function destroy(Credit $credit)
    {
        //
    }
}
