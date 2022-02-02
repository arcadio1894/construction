<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Entry;
use App\SupplierCredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->doesntHave('credit')
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
        foreach( $credits as $credit )
        {
            if ( isset($credit->date_expiration) )
            {
                $currentDate= Carbon::now();
                $date_expire = Carbon::parse($credit->date_expiration);

                $difference = $date_expire->diffInDays($currentDate);
                $credit->days_to_expiration = $difference;

                if ( $date_expire < $currentDate)
                {
                    $credit->state = 'expired';
                } else {
                    $credit->state = 'by_expire';
                }

                $credit->save();
            }

        }
        $credits = SupplierCredit::with('supplier')
            ->with('entry')
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($credits)->toJson();
    }

    public function addInvoiceToCredit($idEntry)
    {
        DB::beginTransaction();
        try {
            $entry = Entry::with('supplier')->find($idEntry);

            $credit = SupplierCredit::create([
                'supplier_id' => $entry->supplier->id,
                'entry_id' => $entry->id,
                'invoice' => $entry->invoice,
                'image_invoice' => $entry->image,
                'purchase_order' => $entry->purchase_order,
                'total_soles' => ($entry->currency_invoice == 'PEN') ? $entry->total:null,
                'total_dollars' => ($entry->currency_invoice == 'USD') ? $entry->total:null,
                'date_issue' => $entry->date_entry,
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Factura agregada con éxito.'], 200);

    }

    public function getCreditById( $credit_id )
    {
        $credit = SupplierCredit::with('supplier')
            ->with('entry')->find($credit_id);

        return response()->json(['credit' => $credit], 200);
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
