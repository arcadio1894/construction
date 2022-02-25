<?php

namespace App\Http\Controllers;

use App\Entry;
use App\SupplierCredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach( $credits as $credit )
        {
            if ( isset($credit->date_expiration) && $credit->state_credit != 'paid_out' )
            {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ( (int)$dias_to_expire < 4 && (int)$dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire == 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }
            }

        }
        $credits = SupplierCredit::with('supplier')
            ->with('purchase')
            ->with('service')
            ->with('deadline')
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
            ->with('purchase')
            ->with('service')
            ->with('deadline')
            ->find($credit_id);

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

    public function update(Request $request)
    {
        //dd($request);
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($request->get('credit_id'));
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados
            $credit->date_issue = Carbon::createFromFormat('d/m/Y', $request->get('date_issue'), 'America/Lima');
            $credit->date_expiration = Carbon::createFromFormat('d/m/Y', $request->get('date_expiration'), 'America/Lima' );
            $credit->days_to_expiration = (int) $request->get('days_to_expiration');
            $credit->observation = $request->get('observation');
            $credit->save();

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ( (int)$dias_to_expire < 4 && (int)$dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire == 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }

                if ( $dias_to_expire > 4 )
                {
                    $credit->state_credit = 'outstanding';
                    $credit->save();
                }
            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }


            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Crédito modificado correctamente'], 200);

    }

    public function paid(Request $request)
    {
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($request->get('credit_id'));
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $credit->observation_extra = $request->get('observation2');
                $credit->date_paid = Carbon::createFromFormat('d/m/Y', $request->get('date_paid'), 'America/Lima' );
                $credit->state_credit = 'paid_out';

                if (!$request->file('image_paid')) {
                    $credit->image_paid = 'no_image.png';
                    $credit->save();
                } else {
                    $path = public_path().'/images/credits/';
                    $image = $request->file('image_paid');
                    $extension = $request->file('image_paid')->getClientOriginalExtension();
                    //$filename = $entry->id . '.' . $extension;
                    if ( $extension != 'pdf' )
                    {
                        $filename = $credit->id . '.jpg';
                        $img = Image::make($image);
                        $img->orientate();
                        $img->save($path.$filename, 80, 'jpg');
                        //$request->file('image')->move($path, $filename);
                        $credit->image_paid = $filename;
                        $credit->save();
                    } else {
                        $filename = 'pdf'.$credit->id . '.' .$extension;
                        $request->file('image_paid')->move($path, $filename);
                        $credit->image_paid = $filename;
                        $credit->save();
                    }

                }
                $credit->save();

            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Crédito pagado correctamente'], 200);

    }

    public function cancelPayCredit($idCredit)
    {
        DB::beginTransaction();
        try {

            $credit = SupplierCredit::find($idCredit);
            // TODO: Solo se guarda las fechas, dias para expirar y la observacion
            // TODO: Se actualiza los estados

            if ( isset($credit->date_expiration) && ($credit->invoice!=null || $credit->invoice!='') )
            {
                $fecha = Carbon::parse($credit->date_expiration, 'America/Lima');
                $dias_to_expire = $fecha->diffInDays(Carbon::now('America/Lima'));
                $credit->days_to_expiration = (int)$dias_to_expire;
                $credit->save();

                if ( (int)$dias_to_expire < 4 && (int)$dias_to_expire > 0 )
                {
                    $credit->state_credit = 'by_expire';
                    $credit->save();
                }

                if ( $dias_to_expire == 0 )
                {
                    $credit->state_credit = 'expired';
                    $credit->save();
                }

                if ( $dias_to_expire > 4 )
                {
                    $credit->state_credit = 'outstanding';
                    $credit->save();
                }

                if ( $credit->image_paid != 'no_image.png' || $credit->image_paid != null )
                {
                    $path = public_path().'/images/credits/'.$credit->image_paid;
                    unlink($path);
                    $credit->image_paid = null;
                    $credit->save();
                }

                $credit->date_paid = null;
                $credit->observation_extra = '';
                $credit->save();

            } else {
                return response()->json(['message' => "No tiene una factura aún"], 422);
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Pago cancelado correctamente'], 200);

    }
}
