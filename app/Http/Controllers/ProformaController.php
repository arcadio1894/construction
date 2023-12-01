<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
use App\Customer;
use App\PaymentDeadline;
use App\Proforma;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProformaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $paymentDeadlines = PaymentDeadline::where('type', 'quotes')->get();
        $customers = Customer::all();

        return view('proforma.index', compact('permissions', 'paymentDeadlines', 'customers'));

    }

    public function getDataProformas(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        /*$categoryEquipmentid = $request->input('category_Equipment_id');
        $largeDefaultEquipment = $request->input('large_Default_Equipment');
        $widthDefaultEquipment = $request->input('width_Default_Equipment');
        $highDefaultEquipment = $request->input('high_Default_Equipment');*/
        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(4);
        $query = Proforma::where('created_at', '>=', $date4MonthAgo)
            ->orderBy('created_at', 'DESC');

        // Aplicar filtros si se proporcionan
        /*if ($largeDefaultEquipment) {
            $query->where('large', $largeDefaultEquipment);

        }

        if ($widthDefaultEquipment) {
            $query->where('width', $widthDefaultEquipment);

        }

        if ($highDefaultEquipment) {
            $query->where('high', $highDefaultEquipment);

        }*/

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $proformas = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        //dd($proformas);

        $arrayProformas = [];

        foreach ( $proformas as $proforma )
        {
            if ( $proforma->state == 'created' )
            {
                $state = '<span class="badge bg-primary">Creada</span>';
            } elseif ( $proforma->state == 'confirmed' ) {
                $state = '<span class="badge bg-gradient-navy text-white">V.B. '. $proforma->date_vb_proforma->format('d/m/Y') .' - '. $proforma->user_vb->name.'</span>';
            } elseif ( $proforma->state == 'destroy' ) {
                $state = '<span class="badge bg-danger">Cancelada</span>';
            } elseif ( $proforma->state == 'expired' ) {
                $state = '<span class="badge bg-warning">Expiró</span>';
            } else {
                $state = '<span class="badge bg-secondary">Sin Estado</span>';
            }

            array_push($arrayProformas, [
                "id" => $proforma->id,
                "code" => $proforma->code,
                "description" => $proforma->description_quote,
                "date_quote" => ($proforma->date_quote == null) ? '': $proforma->date_quote->format('d/m/Y'),
                "date_validate" => ($proforma->date_validate == null) ? '': $proforma->date_validate->format('d/m/Y'),
                "deadline" => ($proforma->payment_deadline_id == null) ? '': $proforma->deadline->description,
                "delivery_time" => $proforma->delivery_time,
                "customer" => ($proforma->customer_id == null) ? '': $proforma->customer->business_name,
                "total_sin_igv" => round(($proforma->total_proforma)/1.18, 0),
                "total_con_igv" => round($proforma->total_proforma, 0),
                "currency" => $proforma->currency,
                "state" => $state,
                "created_at" => $proforma->created_at->format('d/m/Y'),
                "creator" => ($proforma->user_creator == null) ? '': $proforma->creator->name
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayProformas, 'pagination' => $pagination];
    }

    public function create()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $customers = Customer::all();
        $maxId = Proforma::max('id')+1;
        $length = 5;
        $codeQuote = 'PCOT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
        $paymentDeadlines = PaymentDeadline::where('type', 'quotes')->get();
        $categories = CategoryEquipment::all();

        // TODO: Creamos la pre cotización vacía
        /*$proforma = Proforma::create([
            'code' => $codeQuote,
            'total' => 0,
            'state' => 'created',
            'currency' => 'USD',
            'user_creator' => Auth::id()
        ]);*/

        return view('proforma.create', compact('categories','customers','codeQuote', 'permissions', 'paymentDeadlines'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function show(Proforma $proforma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function edit(Proforma $proforma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proforma $proforma)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proforma $proforma)
    {
        //
    }
}
