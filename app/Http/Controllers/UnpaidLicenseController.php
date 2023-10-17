<?php

namespace App\Http\Controllers;

use App\UnpaidLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnpaidLicenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('unpaidLicense.index', compact('permissions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(UnpaidLicense $unpaidLicense)
    {
        //
    }

    public function edit(UnpaidLicense $unpaidLicense)
    {
        //
    }

    public function update(Request $request, UnpaidLicense $unpaidLicense)
    {
        //
    }

    public function destroy(UnpaidLicense $unpaidLicense)
    {
        //
    }

    public function getAllUnpaidLicenses()
    {
        $unpaidLicenses = UnpaidLicense::select('id', 'date_start', 'date_end', 'file', 'worker_id', 'created_at', 'reason')
            ->with('worker')
            ->orderBy('created_at', 'DESC')
            ->get();
        return datatables($unpaidLicenses)->toJson();

    }
}
