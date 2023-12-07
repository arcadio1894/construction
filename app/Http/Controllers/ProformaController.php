<?php

namespace App\Http\Controllers;

use App\Audit;
use App\CategoryEquipment;
use App\Customer;
use App\DefaultEquipment;
use App\DefaultEquipmentConsumable;
use App\DefaultEquipmentMaterial;
use App\DefaultEquipmentTurnstile;
use App\DefaultEquipmentWorkForce;
use App\EquipmentProforma;
use App\EquipmentProformaConsumable;
use App\EquipmentProformaMaterial;
use App\EquipmentProformaTurnstiles;
use App\EquipmentProformaWorkdays;
use App\EquipmentProformaWorkforces;
use App\Http\Requests\ProformaEditRequest;
use App\Http\Requests\ProformaStoreRequest;
use App\Material;
use App\Notification;
use App\NotificationUser;
use App\PaymentDeadline;
use App\Proforma;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

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
                $state = '<span class="badge bg-gradient-navy text-white">V.B. '. $proforma->date_vb_proforma->format('d/m/Y') .' - <br>'. $proforma->user_vb->name.'</span>';
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
                "estado" => $proforma->state,
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

    public function getDataEquipments(Request $request)
    {
        $category = $request->input('category');
        $nameEquipment = $request->input('nameEquipment');
        $large = $request->input('length');
        $width = $request->input('width');
        $high = $request->input('high');

        $query = DefaultEquipment::where('category_equipment_id', $category)
            ->orderBy('created_at', 'DESC');

        // Aplicar filtros si se proporcionan
        if ($nameEquipment) {
            $query->where('description', 'like', '%'.$nameEquipment.'%');
        }

        if ($large) {
            $query->where('large', $large);
        }

        if ($width) {
            $query->where('width', $width);
        }

        if ($high) {
            $query->where('high', $high);
        }



        $equipments = $query->get();

        //dd($proformas);

        $arrayEquipments = [];

        foreach ( $equipments as $equipment )
        {
            array_push($arrayEquipments, [
                "id" => $equipment->id,
                "description" => $equipment->description,
                "large" => $equipment->large,
                "width" => $equipment->width,
                "high" => $equipment->high,
            ]);
        }

        return ['equipments' => $arrayEquipments];
    }

    public function getDataEquipmentDefault($equipment_id)
    {
        $equipment = DefaultEquipment::find($equipment_id);
        // TODO: Actualizar los precios
        $flagChange = false;
        foreach ( $equipment->materials as $equipment_material )
        {
            if ( $equipment_material->unit_price !== $equipment_material->material->unit_price )
            {
                $flagChange = true;
                $equipment_material->unit_price = $equipment_material->material->unit_price;
                $equipment_material->total_price = $equipment_material->material->unit_price * $equipment_material->quantity;
                $equipment_material->save();
            }
        }

        foreach ( $equipment->consumables as $equipment_consumable )
        {
            if ( $equipment_consumable->unit_price !== $equipment_consumable->material->unit_price )
            {
                $flagChange = true;
                $equipment_consumable->unit_price = $equipment_consumable->material->unit_price;
                $equipment_consumable->total_price = $equipment_consumable->material->unit_price * $equipment_consumable->quantity;
                $equipment_consumable->save();
            }
        }

        return response()->json([
            "change" => $flagChange,
            "id" => $equipment->id,
            "nEquipment" => $equipment->description,
            "qEquipment" => 1,
            "pEquipment" => round(($equipment->total_equipment/1)/1.18, 2),
            "uEquipment" => $equipment->utility,
            "rlEquipment" => round($equipment->rent + $equipment->letter, 2),
            "uPEquipment" => round(($equipment->total_equipment_utility/1.18)/1, 2),
            "tEquipment" => round($equipment->total_equipment_utility/1.18, 2)
        ]);
    }

    public function store(ProformaStoreRequest $request)
    {
        $begin = microtime(true);
//        dump($request);
//        dd();
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $maxCode = Proforma::max('id');
            $maxId = $maxCode + 1;
            $length = 5;
            //$codeQuote = 'COT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $proforma = Proforma::create([
                'code' => '',
                'description_quote' => $request->get('code_description'),
                'date_quote' => ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now(),
                'date_validate' => ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now()->addDays(5),
                'delivery_time' => ($request->has('delivery_time')) ? $request->get('delivery_time') : '',
                'customer_id' => ($request->has('customer_id')) ? $request->get('customer_id') : null,
                'contact_id' => ($request->has('contact_id')) ? $request->get('contact_id') : null,
                'payment_deadline_id' => ($request->has('payment_deadline')) ? $request->get('payment_deadline') : null,
                'state' => 'created',
                'currency' => 'USD',
                'user_creator' => Auth::id(),
                'observations' => $request->get('observations'),
            ]);

            $codeQuote = '';
            if ( $maxId < $proforma->id ){
                $codeQuote = 'PCOT-'.str_pad($proforma->id,$length,"0", STR_PAD_LEFT);
                $proforma->code = $codeQuote;
                $proforma->save();
            } else {
                $codeQuote = 'PCOT-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $proforma->code = $codeQuote;
                $proforma->save();
            }

            $equipments = json_decode($request->get('equipments'));

            $totalQuote = 0;

            for ( $i=0; $i<sizeof($equipments); $i++ )
            {
                $defaultEquipment = DefaultEquipment::find($equipments[$i]->id);
                $equipment = EquipmentProforma::create([
                    'proforma_id' => $proforma->id,
                    'default_equipment_id' => $defaultEquipment->id,
                    'description' =>($defaultEquipment->description == "" || $defaultEquipment->description == null) ? '':$defaultEquipment->description,
                    'detail' => ($defaultEquipment->detail == "" || $defaultEquipment->detail == null) ? '':$defaultEquipment->detail,
                    'quantity' => 1,
                    'utility' => $defaultEquipment->utility,
                    'rent' => $defaultEquipment->rent,
                    'letter' => $defaultEquipment->letter,
                    'total' => $defaultEquipment->total*1.18
                ]);

                $totalMaterial = 0;

                $totalConsumable = 0;

                $totalWorkforces = 0;

                $totalTornos = 0;

                $totalDias = 0;

                $materials = $defaultEquipment->materials;

                $consumables = $defaultEquipment->consumables;

                $workforces = $defaultEquipment->workforces;

                $tornos = $defaultEquipment->turnstiles;

                $dias = $defaultEquipment->workdays;

                for ( $j=0; $j<sizeof($materials); $j++ )
                {
                    $equipmentMaterial = EquipmentProformaMaterial::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $materials[$j]->material_id,
                        'quantity' => (float) $materials[$j]->quantity,
                        'unit_price' => (float) $materials[$j]->unit_price,
                        'length' => (float) ($materials[$j]->length == '') ? 0: $materials[$j]->length,
                        'width' => (float) ($materials[$j]->width == '') ? 0: $materials[$j]->width,
                        'percentage' => (float) $materials[$j]->percentage,
                        'total_price' => (float) $materials[$j]->total_price,
                    ]);

                    $totalMaterial += $equipmentMaterial->total_price;
                }

                for ( $k=0; $k<sizeof($consumables); $k++ )
                {
                    $equipmentConsumable = EquipmentProformaConsumable::create([
                        'equipment_id' => $equipment->id,
                        'material_id' => $consumables[$k]->material_id,
                        'quantity' => (float) $consumables[$k]->quantity,
                        'unit_price' => (float) $consumables[$k]->unit_price,
                        'total_price' => (float) $consumables[$k]->total_price,
                    ]);

                    $totalConsumable += $equipmentConsumable->total_price;
                }

                for ( $w=0; $w<sizeof($workforces); $w++ )
                {
                    $equipmentWorkforce = EquipmentProformaWorkforces::create([
                        'equipment_id' => $equipment->id,
                        'description' => $workforces[$w]->description,
                        'unit_price' => (float) $workforces[$w]->unit_price,
                        'quantity' => (float) $workforces[$w]->quantity,
                        'total_price' => (float) $workforces[$w]->total_price,
                        'unit' => $workforces[$w]->unit,
                    ]);

                    $totalWorkforces += $equipmentWorkforce->total_price;
                }

                for ( $r=0; $r<sizeof($tornos); $r++ )
                {
                    $equipmenttornos = EquipmentProformaTurnstiles::create([
                        'equipment_id' => $equipment->id,
                        'description' => $tornos[$r]->description,
                        'unit_price' => (float) $tornos[$r]->unit_price,
                        'quantity' => (float) $tornos[$r]->quantity,
                        'total_price' => (float) $tornos[$r]->total_price
                    ]);

                    $totalTornos += $equipmenttornos->total_price;
                }

                for ( $d=0; $d<sizeof($dias); $d++ )
                {
                    $equipmentdias = EquipmentProformaWorkdays::create([
                        'equipment_id' => $equipment->id,
                        'description' => $dias[$d]->description,
                        'quantityPerson' => (float) $dias[$d]->quantityPerson,
                        'hoursPerPerson' => (float) $dias[$d]->hoursPerPerson,
                        'pricePerHour' => (float) $dias[$d]->pricePerHour,
                        'total_price' => (float) $dias[$d]->total_price
                    ]);

                    $totalDias += $equipmentdias->total_price;
                }

                $totalEquipo = (($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos) )+$totalDias;
                $totalEquipmentU = $totalEquipo*(($equipment->utility/100)+1);
                $totalEquipmentL = $totalEquipmentU*(($equipment->letter/100)+1);
                $totalEquipmentR = $totalEquipmentL*(($equipment->rent/100)+1);

                $totalQuote += $totalEquipmentR;

                $equipment->total = $totalEquipo;

                $equipment->save();
            }

            $proforma->total = $totalQuote;

            $proforma->save();

            // Crear notificacion
            $notification = Notification::create([
                'content' => $proforma->code.' creada por '.Auth::user()->name,
                'reason_for_creation' => 'create_prequote',
                'user_id' => Auth::user()->id,
                'url_go' => ""
            ]);

            // Roles adecuados para recibir esta notificación admin, logistica
            $users = User::role(['admin', 'principal' , 'logistic'])->get();
            foreach ( $users as $user )
            {
                if ( $user->id != Auth::user()->id )
                {
                    foreach ( $user->roles as $role )
                    {
                        NotificationUser::create([
                            'notification_id' => $notification->id,
                            'role_id' => $role->id,
                            'user_id' => $user->id,
                            'read' => false,
                            'date_read' => null,
                            'date_delete' => null
                        ]);
                    }
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar pre cotizacion POST',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Pre Cotización '.$codeQuote.' guardada con éxito.'], 200);

    }

    public function show($proforma_id)
    {
        $proforma = Proforma::where('id', $proforma_id)
            ->with('customer')
            ->with('deadline')
            ->with(['equipments'])->first();
        //dump($quote);

        /*Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver cotizacion VISTA',
            'time' => $end
        ]);*/
        return view('proforma.show', compact('proforma'));

    }

    public function edit($proforma_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $customers = Customer::all();
        $paymentDeadlines = PaymentDeadline::where('type', 'quotes')->get();
        $categories = CategoryEquipment::all();
        $proforma = Proforma::where('id', $proforma_id)
            ->with('customer')
            ->with('deadline')
            ->with(['equipments'])->first();
        //dump($quote);

        /*Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver cotizacion VISTA',
            'time' => $end
        ]);*/
        return view('proforma.edit', compact('proforma', 'permissions', 'customers', 'paymentDeadlines', 'categories'));
    }

    public function update(ProformaEditRequest $request)
    {
        $begin = microtime(true);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $quote = Proforma::find($request->get('proforma_id'));

            $quote->description_quote = $request->get('code_description');
            $quote->date_quote = ($request->has('date_quote')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_quote')) : Carbon::now();
            $quote->date_validate = ($request->has('date_validate')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_validate')) : Carbon::now()->addDays(5);
            $quote->payment_deadline_id = ($request->has('payment_deadline')) ? $request->get('payment_deadline') : null;
            $quote->delivery_time = ($request->has('delivery_time')) ? $request->get('delivery_time') : '';
            $quote->customer_id = ($request->has('customer_id')) ? $request->get('customer_id') : null;
            $quote->contact_id = ($request->has('contact_id')) ? $request->get('contact_id') : null;
            $quote->currency_invoice = 'USD';
            $quote->currency_compra = null;
            $quote->currency_venta = null;
            $quote->total_soles = 0;
            $quote->save();

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Editar pre cotizaciones POST',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Nuevos equipos guardados con éxito.'], 200);

    }

    public function destroy($proforma_id)
    {
        DB::beginTransaction();
        try {
                $proforma = Proforma::find($proforma_id);
                $proforma->state = 'destroy';
                $proforma->save();
                DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Pre Cotización '.$proforma->code.' eliminada con éxito.'], 200);
    }

    public function printProformaToCustomer($id)
    {
        // Eliminamos elos archivos

        $proforma = Proforma::where('id', $id)
            ->with('customer')
            ->with('deadline')
            ->with(['equipments'])->first();

        $view = view('proforma.proformaCustomer', compact('proforma'));

        $pdf = PDF::loadHTML($view);

        $description = str_replace(array('"', "'", "/"),'',$proforma->description_quote);

        $name = $proforma->code . ' '. ltrim(rtrim($description)) . '.pdf';
        return $pdf->stream($name);

        //return $pdf->stream($name);
    }

    public function vistoBuenoProforma($id)
    {
        DB::beginTransaction();
        try {
            $proforma = Proforma::find($id);

            //$quote->order_execution = $codeOrderExecution;
            $proforma->vb_proforma = 1;
            $proforma->date_vb_proforma= Carbon::now('America/Lima');
            $proforma->user_vb_proforma= Auth::id();
            $proforma->state = 'confirmed';
            $proforma->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Visto bueno guardado.'], 200);

    }
}
