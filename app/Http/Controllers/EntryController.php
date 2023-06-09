<?php

namespace App\Http\Controllers;

use App\Audit;
use App\DetailEntry;
use App\Entry;
use App\EntryImage;
use App\FollowMaterial;
use App\Http\Requests\StoreEntryPurchaseOrderRequest;
use App\Http\Requests\StoreEntryPurchaseRequest;
use App\Http\Requests\StoreOrderPurchaseRequest;
use App\Http\Requests\UpdateEntryPurchaseRequest;
use App\Item;
use App\Material;
use App\MaterialOrder;
use App\Notification;
use App\NotificationUser;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use App\PaymentDeadline;
use App\Supplier;
use App\SupplierCredit;
use App\Typescrap;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade as PDF;

class EntryController extends Controller
{

    public function indexEntryPurchase()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('entry.index_entry_purchase', compact('permissions'));
    }

    public function indexEntryScraps()
    {
        return view('entry.index_entry_scrap');
    }

    public function createEntryPurchase()
    {
        $suppliers = Supplier::all();
        return view('entry.create_entry_purchase', compact('suppliers'));
    }

    public function createEntryScrap()
    {
        return view('entry.create_entry_scrap');
    }

    public function storeEntryPurchase(StoreEntryPurchaseRequest $request)
    {
        $begin = microtime(true);
        //dd($request->get('deferred_invoice'));
        $validated = $request->validated();

        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        //dump($request->get('date_invoice'));
        $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_invoice'));

        //dump();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha->format('Y-m-d'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $tipoCambioSunat = json_decode($response);

        if ( $request->get('purchase_order') != '' || $request->get('purchase_order') != null )
        {
            $order_purchase1 = OrderPurchase::where('code', $request->get('purchase_order'))->first();

            if ( isset($order_purchase1) )
            {
                return response()->json([
                    'message' => "No se encontró la orden de compra indicada"
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            //dump($tipoCambioSunat->compra);
            $entry = Entry::create([
                'referral_guide' => $request->get('referral_guide'),
                'purchase_order' => $request->get('purchase_order'),
                'invoice' => $request->get('invoice'),
                'deferred_invoice' => ($request->has('deferred_invoice')) ? $request->get('deferred_invoice'):'off',
                'currency_invoice' => ($request->has('currency_invoice')) ? 'USD':'PEN',
                'supplier_id' => $request->get('supplier_id'),
                'entry_type' => $request->get('entry_type'),
                'date_entry' => Carbon::createFromFormat('d/m/Y', $request->get('date_invoice')),
                'finance' => false,
                'currency_compra' => (float) $tipoCambioSunat->compra,
                'currency_venta' => (float) $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
            ]);

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $entry->image = 'no_image.png';
                $entry->save();
            } else {
                $path = public_path().'/images/entries/';
                $image = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                //$filename = $entry->id . '.' . $extension;
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                }

            }

            if (!$request->file('imageOb')) {
                $entry->imageOb = 'no_image.png';
                $entry->save();
            } else {
                $path = public_path().'/images/entries/observations/';
                $image = $request->file('imageOb');
                $extension = $image->getClientOriginalExtension();
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('imageOb')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                }

            }

            $items = json_decode($request->get('items'));

            //dd($item->id);
            $materials_id = [];

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                array_push($materials_id, $items[$i]->id_material);
            }

            $counter = array_count_values($materials_id);
            //dd($counter);

            foreach ( $counter as $id_material => $count )
            {
                $material = Material::find($id_material);
                $material->stock_current = $material->stock_current + $count;
                $material->save();

                // TODO: ORDER_QUANTITY sera tomada de la orden de compra
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_id' => $id_material,
                    'ordered_quantity' => $count,
                    'entered_quantity' => $count,
                ]);

                // TODO: Revisamos si hay un material en seguimiento y creamos
                // TODO: la notificacion y cambiamos el estado
                $follows = FollowMaterial::where('material_id', $id_material)
                    ->get();
                if ( !$follows->isEmpty() )
                {
                    // TODO: Creamos notificacion y cambiamos el estado
                    // Crear notificacion
                    $notification = Notification::create([
                        'content' => 'El material ' . $detail_entry->material->full_description . ' ha sido ingresado.',
                        'reason_for_creation' => 'follow_material',
                        'user_id' => Auth::user()->id,
                        'url_go' => route('follow.index')
                    ]);

                    // Roles adecuados para recibir esta notificación admin, logistica
                    $users = User::role(['admin', 'operator'])->get();
                    foreach ( $users as $user )
                    {
                        $followUsers = FollowMaterial::where('material_id', $detail_entry->material_id)
                            ->where('user_id', $user->id)
                            ->get();
                        if ( !$followUsers->isEmpty() )
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
                    foreach ( $follows as $follow )
                    {
                        $follow->state = 'in_warehouse';
                        $follow->save();
                    }
                }

                //dd($id_material .' '. $count);
                $total_detail = 0;
                for ( $i=0; $i<sizeof($items); $i++ )
                {
                    if( $detail_entry->material_id == $items[$i]->id_material )
                    {
                        if ( $entry->currency_invoice === 'PEN' )
                        {
                            $precio1 = round((float)$items[$i]->price,2) / (float) $entry->currency_compra;
                            $price1 = ($detail_entry->material->price > $precio1) ? $detail_entry->material->price : $precio1;
                            $materialS = Material::find($detail_entry->material_id);
                            if ( $materialS->price < $price1 )
                            {
                                $materialS->unit_price = $price1;
                                $materialS->save();

                                $detail_entry->unit_price = round((float)$items[$i]->price,2);
                                $detail_entry->save();
                            } else {
                                $detail_entry->unit_price = round((float)$items[$i]->price,2);
                                $detail_entry->save();
                            }
                            //dd($detail_entry->material->materialType);
                            if ( isset($detail_entry->material->typeScrap) )
                            {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => (float)$detail_entry->material->typeScrap->length,
                                    'width' => (float)$detail_entry->material->typeScrap->width,
                                    'weight' => 0,
                                    'price' => round((float)$items[$i]->price,2),
                                    'percentage' => 1,
                                    'typescrap_id' => $detail_entry->material->typeScrap->id,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            } else {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => 0,
                                    'width' => 0,
                                    'weight' => 0,
                                    'price' => round((float)$items[$i]->price,2),
                                    'percentage' => 1,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            }
                        } else {
                            $price = ((float)$detail_entry->material->unit_price > round((float)$items[$i]->price,2)) ? $detail_entry->material->unit_price : round((float)$items[$i]->price,2);
                            $materialS = Material::find($detail_entry->material_id);
                            if ( (float)$materialS->unit_price < round((float)$items[$i]->price,2) )
                            {
                                $materialS->unit_price = (float) round((float)$items[$i]->price,2);
                                $materialS->save();

                                $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                                $detail_entry->save();
                            } else {
                                $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                                $detail_entry->save();
                            }
                            //dd($detail_entry->material->materialType);
                            if ( isset($detail_entry->material->typeScrap) )
                            {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => (float)$detail_entry->material->typeScrap->length,
                                    'width' => (float)$detail_entry->material->typeScrap->width,
                                    'weight' => 0,
                                    'price' => (float)$price,
                                    'percentage' => 1,
                                    'typescrap_id' => $detail_entry->material->typeScrap->id,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            } else {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => 0,
                                    'width' => 0,
                                    'weight' => 0,
                                    'price' => (float)$price,
                                    'percentage' => 1,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            }
                        }


                    }
                }
                $detail_entry->total_detail = round($total_detail,2);
                $detail_entry->save();
            }


            /* SI ( En el campo factura y en (Orden Compra/Servicio) ) AND Diferente a 000
                Entonces
                SI ( Existe en la tabla creditos ) ENTONCES
                actualiza la factura en la tabla de creditos
            */
            if ( ($entry->invoice != '' || $entry->invoice != null) )
            {
                if ( $entry->purchase_order != '' || $entry->purchase_order != null )
                {
                    $order_purchase = OrderPurchase::where('code', $entry->purchase_order)->first();
                    /*$credit = SupplierCredit::with('deadline')
                        ->where('code_order', $entry->purchase_order)
                        ->where('state_credit', 'outstanding')->first();*/

                    if ( isset($order_purchase) )
                    {
                        /*if ( $credit->invoice != "" || $credit->invoice != null )
                        {
                            //$credit->delete();
                            $deadline = PaymentDeadline::find($credit->deadline->id);
                            $fecha_issue = Carbon::parse($entry->date_entry);
                            $fecha_expiration = $fecha_issue->addDays($deadline->days);
                            // TODO: Poner dias
                            $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                            $credit->supplier_id = $entry->supplier_id;
                            $credit->invoice = $credit->invoice.' | '.$entry->invoice;
                            $credit->image_invoice = $entry->image;
                            $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                            $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                            $credit->date_issue = $entry->date_entry;
                            $credit->date_expiration = $fecha_expiration;
                            $credit->days_to_expiration = $dias_to_expire;
                            $credit->code_order = $entry->purchase_order;
                            $credit->save();
                        } else {
                            //$credit->delete();
                            $deadline = PaymentDeadline::find($credit->deadline->id);
                            $fecha_issue = Carbon::parse($entry->date_entry);
                            $fecha_expiration = $fecha_issue->addDays($deadline->days);
                            // TODO: Poner dias
                            $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                            $credit->supplier_id = $entry->supplier_id;
                            $credit->invoice = $entry->invoice;
                            $credit->image_invoice = $entry->image;
                            $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                            $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                            $credit->date_issue = $entry->date_entry;
                            $credit->date_expiration = $fecha_expiration;
                            $credit->days_to_expiration = $dias_to_expire;
                            $credit->code_order = $entry->purchase_order;
                            $credit->save();
                        }*/
                        if ( isset($order_purchase->deadline) )
                        {
                            if ( $order_purchase->deadline->credit == 1 || $order_purchase->deadline->credit == true )
                            {
                                $deadline = PaymentDeadline::find($order_purchase->deadline->id);
                                $fecha_issue = Carbon::parse($entry->date_entry);
                                $fecha_expiration = $fecha_issue->addDays($deadline->days);
                                // TODO: Poner dias
                                $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                                $credit = SupplierCredit::create([
                                    'supplier_id' => $order_purchase->supplier->id,
                                    'invoice' => ($this->onlyZeros($entry->invoice) == true) ? null:$entry->invoice,
                                    'image_invoice' => $entry->image,
                                    'total_soles' => ($order_purchase->currency_order == 'PEN') ? (float)$entry->total:null,
                                    'total_dollars' => ($order_purchase->currency_order == 'USD') ? (float)$entry->total:null,
                                    'date_issue' => Carbon::parse($entry->date_entry),
                                    'order_purchase_id' => $order_purchase->id,
                                    'state_credit' => 'outstanding',
                                    'order_service_id' => null,
                                    'date_expiration' => $fecha_expiration,
                                    'days_to_expiration' => $dias_to_expire,
                                    'code_order' => $order_purchase->code,
                                    'payment_deadline_id' => $order_purchase->payment_deadline_id,
                                    'entry_id' => $entry->id
                                ]);
                            }
                        }

                    }
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Crear Ingreso Almacen',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por compra guardado con éxito.'], 200);

    }

    public function storeEntryScrap(Request $request)
    {
        DB::beginTransaction();
        try {
            $entry = Entry::create([
                'entry_type' => "Retacería",
                'date_entry' => Carbon::now(),
                'finance' => false
            ]);

            $item_selected = json_decode($request->get('item'));
            //dd($item_selected[0]->detailEntry);

            $detail_entry = DetailEntry::create([
                'entry_id' => $entry->id,
                'material_id' => $item_selected[0]->material_id,
            ]);

            // TODO: Crear el item

            $item = Item::create([
                'detail_entry_id' => $detail_entry->id,
                'material_id' => $item_selected[0]->material_id,
                'code' => $item_selected[0]->code,
                'length' => (float)  $item_selected[0]->length,
                'width' => (float) $item_selected[0]->width,
                'weight' => (float)  $item_selected[0]->weight,
                'price' => (float)  $item_selected[0]->price,
                'typescrap_id' => $item_selected[0]->typescrap_id,
                'location_id' => $item_selected[0]->location_id,
                'state' => $item_selected[0]->state,
                'state_item' => 'scraped'
            ]);

            // TODO: Eliminar el item anterior
            $item_deleted = Item::find($item_selected[0]->id);
            $item_deleted->percentage = 0;
            $item_deleted->save();
            //$item_deleted->delete();

            // TODO: Actualizar la cantidad en el material
            // TODO: Primero restar uno y luego sumar AreaReal/AreaTotal
            $material = Material::with('typeScrap')->find($item->material_id);
            $porcentaje = 0;
            if( isset($material->typeScrap) && ($material->typeScrap->id == 1 || $material->typeScrap->id == 2)  )
            {
                $porcentaje = ($item->length*$item->width)/($material->typeScrap->length*$material->typeScrap->width);
                $item->percentage = $porcentaje;
                $item->save();
            }
            // TODO: Agregamos los tubos pequeños
            if( isset($material->typeScrap) && ($material->typeScrap->id == 3 || $material->typeScrap->id == 4) )
            {
                $porcentaje = ($item->length)/($material->typeScrap->length);
                $item->percentage = $porcentaje;
                $item->save();
            }
            $material->stock_current = $material->stock_current + $porcentaje;
            $material->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por retacería guardado con éxito.'], 200);

    }

    public function show(Entry $entry)
    {
        //
    }

    public function editEntryPurchase(Entry $entry)
    {
        $suppliers = Supplier::all();
        return view('entry.edit_entry_purchase', compact('entry', 'suppliers'));
    }

    public function showEntryPurchase(Entry $entry)
    {
        $suppliers = Supplier::all();
        return view('entry.show_entry_purchase', compact('entry', 'suppliers'));
    }

    public function updateEntryPurchase(UpdateEntryPurchaseRequest $request)
    {
        $begin = microtime(true);
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $entry = Entry::find($request->get('entry_id'));
            $entry->referral_guide = $request->get('referral_guide');
            $entry->purchase_order = $request->get('purchase_order');
            $entry->invoice = $request->get('invoice');
            $entry->deferred_invoice = ($request->has('deferred_invoice')) ? $request->get('deferred_invoice'):'off';
            $entry->supplier_id = $request->get('supplier_id');
            $entry->date_entry = Carbon::createFromFormat('d/m/Y', $request->get('date_invoice'));
            $entry->observation = $request->get('observation');
            $entry->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                if ($entry->image == 'no_image.png' || $entry->image == null) {
                    $entry->image = 'no_image.png';
                    $entry->save();
                }
            } else {
                $path = public_path().'/images/entries/';
                $image = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                if (strtoupper($extension) != "PDF" )
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                }
                //$filename = $entry->id . '.' . $extension;
                //$filename = $entry->id . '.jpg';
                //$img = Image::make($image);
                //$img->orientate();
                //$img->save($path.$filename, 80, 'jpg');
                //$request->file('image')->move($path, $filename);
                //$entry->image = $filename;
                //$entry->save();
            }

            if (!$request->file('imageOb')) {
                if ($entry->imageOb == 'no_image.png' || $entry->imageOb == null) {
                    $entry->imageOb = 'no_image.png';
                    $entry->save();
                }
            } else {
                $path = public_path().'/images/entries/observations/';
                $image = $request->file('imageOb');
                $extension = $image->getClientOriginalExtension();
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('imageOb')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                }
                //$filename = $entry->id . '.jpg';
                //$img = Image::make($image);
                //$img->orientate();
                //$img->save($path.$filename, 80, 'jpg');
                //$request->file('image')->move($path, $filename);
                //$entry->imageOb = $filename;
                //$entry->save();
            }

            /* SI ( En el campo factura y en (Orden Compra/Servicio) ) AND Diferente a 000
                Entonces
                SI ( Existe en la tabla creditos ) ENTONCES
                actualiza la factura en la tabla de creditos
            */
            if ( $entry->invoice != '' || $entry->invoice != null )
            {
                if ( $entry->purchase_order != '' || $entry->purchase_order != null )
                {
                    $credit = SupplierCredit::with('deadline')
                        ->where('entry_id', $entry->id)
                        ->where('state_credit', 'outstanding')->first();

                    $credit2 = SupplierCredit::with('deadline')
                        ->where('invoice', $entry->invoice)
                        ->where('state_credit', 'outstanding')->first();

                    if ( isset($credit) )
                    {
                        // TODO: Analizar lo de editar de facturas
                        //$credit->delete();

                        $deadline = PaymentDeadline::find($credit->deadline->id);
                        $fecha_issue = Carbon::parse($entry->date_entry);
                        $fecha_expiration = $fecha_issue->addDays($deadline->days);
                        // TODO:poner dias
                        $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                        $credit->supplier_id = $entry->supplier_id;
                        $credit->invoice = ($this->onlyZeros($entry->invoice) == true) ? null:$entry->invoice;
                        $credit->image_invoice = $entry->image;
                        $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                        $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                        $credit->date_issue = $entry->date_entry;
                        $credit->date_expiration = $fecha_expiration;
                        $credit->days_to_expiration = $dias_to_expire;
                        $credit->code_order = $entry->purchase_order;
                        $credit->entry_id = $entry->id;
                        $credit->save();

                    } elseif( isset($credit2) ) {
                        $deadline = PaymentDeadline::find($credit->deadline->id);
                        $fecha_issue = Carbon::parse($entry->date_entry);
                        $fecha_expiration = $fecha_issue->addDays($deadline->days);
                        // TODO:poner dias
                        $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                        $credit->supplier_id = $entry->supplier_id;
                        $credit->invoice = ($this->onlyZeros($entry->invoice) == true) ? null:$entry->invoice;
                        $credit->image_invoice = $entry->image;
                        $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                        $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                        $credit->date_issue = $entry->date_entry;
                        $credit->date_expiration = $fecha_expiration;
                        $credit->days_to_expiration = $dias_to_expire;
                        $credit->code_order = $entry->purchase_order;
                        $credit->entry_id = $entry->id;
                        $credit->save();
                    } else {
                        $order_purchase = OrderPurchase::where('code', $entry->purchase_order)->first();

                        if ( isset($order_purchase) )
                        {
                            /*if ( $credit->invoice != "" || $credit->invoice != null )
                            {
                                //$credit->delete();
                                $deadline = PaymentDeadline::find($credit->deadline->id);
                                $fecha_issue = Carbon::parse($entry->date_entry);
                                $fecha_expiration = $fecha_issue->addDays($deadline->days);
                                // TODO: Poner dias
                                $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                                $credit->supplier_id = $entry->supplier_id;
                                $credit->invoice = $credit->invoice.' | '.$entry->invoice;
                                $credit->image_invoice = $entry->image;
                                $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                                $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                                $credit->date_issue = $entry->date_entry;
                                $credit->date_expiration = $fecha_expiration;
                                $credit->days_to_expiration = $dias_to_expire;
                                $credit->code_order = $entry->purchase_order;
                                $credit->save();
                            } else {
                                //$credit->delete();
                                $deadline = PaymentDeadline::find($credit->deadline->id);
                                $fecha_issue = Carbon::parse($entry->date_entry);
                                $fecha_expiration = $fecha_issue->addDays($deadline->days);
                                // TODO: Poner dias
                                $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                                $credit->supplier_id = $entry->supplier_id;
                                $credit->invoice = $entry->invoice;
                                $credit->image_invoice = $entry->image;
                                $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                                $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                                $credit->date_issue = $entry->date_entry;
                                $credit->date_expiration = $fecha_expiration;
                                $credit->days_to_expiration = $dias_to_expire;
                                $credit->code_order = $entry->purchase_order;
                                $credit->save();
                            }*/
                            if ( isset($order_purchase->deadline) )
                            {
                                if ( $order_purchase->deadline->credit == 1 || $order_purchase->deadline->credit == true )
                                {
                                    $deadline = PaymentDeadline::find($order_purchase->deadline->id);
                                    $fecha_issue = Carbon::parse($entry->date_entry);
                                    $fecha_expiration = $fecha_issue->addDays($deadline->days);
                                    // TODO: Poner dias
                                    $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                                    $credit = SupplierCredit::create([
                                        'supplier_id' => $order_purchase->supplier->id,
                                        'invoice' => ($this->onlyZeros($entry->invoice) == true) ? null:$entry->invoice,
                                        'image_invoice' => $entry->image,
                                        'total_soles' => ($order_purchase->currency_order == 'PEN') ? (float)$entry->total:null,
                                        'total_dollars' => ($order_purchase->currency_order == 'USD') ? (float)$entry->total:null,
                                        'date_issue' => Carbon::parse($entry->date_entry),
                                        'order_purchase_id' => $order_purchase->id,
                                        'state_credit' => 'outstanding',
                                        'order_service_id' => null,
                                        'date_expiration' => $fecha_expiration,
                                        'days_to_expiration' => $dias_to_expire,
                                        'code_order' => $order_purchase->code,
                                        'payment_deadline_id' => $order_purchase->payment_deadline_id,
                                        'entry_id' => $entry->id
                                    ]);
                                }
                            }

                        }
                    }
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Editar Ingreso Almacen',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por compra modificado con éxito.'], 200);

    }

    // No se borró la orden de compra
    public function destroyEntryPurchase(Entry $entry)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            if ( $entry->entry_type === 'Por compra' )
            {
                $details_entry = $entry->details;

                foreach ( $details_entry as $detail )
                {
                    $items = Item::where('detail_entry_id', $detail->id)
                        ->whereIn('state_item', ['reserved','exited'])
                        ->get();
                    if (!isset($items))
                    {
                        return response()->json(['message' => 'Lo sentimos, no se puede eliminar la entrada porque hay items reservados o en salida.'], 422);
                    }

                }

                $order_purchase = OrderPurchase::where('code', $entry->purchase_order)->first();

                foreach ( $details_entry as $detail )
                {
                    $material = Material::find($detail->material_id);
                    $material->stock_current = $material->stock_current - $detail->entered_quantity;
                    $material->save();

                    if ( !is_null( $order_purchase ) )
                    {
                        // TODO: Modificamos los material orders
                        $order_purchase_detail = OrderPurchaseDetail::where('order_purchase_id', $order_purchase->id)
                            ->where('material_id', $material->id)->first();

                        $material_orders = MaterialOrder::where('order_purchase_detail_id', $order_purchase_detail->id)->get();
                        if (isset($material_orders))
                        {
                            foreach ( $material_orders as $material_order )
                            {
                                $material_order->quantity_entered = 0;
                                $material_order->save();
                            }
                        }

                    }

                    $items = Item::where('detail_entry_id', $detail->id)->get();
                    foreach ( $items as $item )
                    {
                        $item->delete();
                    }

                    $detail->delete();
                }

                if ($entry->image !== 'no_image.png')
                {
                    $my_image = public_path().'/images/entries/'.$entry->image;
                    if (@getimagesize($my_image)) {
                        unlink($my_image);
                    }

                }

                $credit = SupplierCredit::with('deadline')
                    ->where('entry_id', $entry->id)
                    ->where('state_credit', 'outstanding')->first();

                if ( isset($credit) )
                {
                    $credit->delete();
                }

                $entry->delete();
            }



            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar Ingreso Almacen',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por compra eliminado con éxito.'], 200);

    }

    public function getJsonEntriesPurchase()
    {
        $begin = microtime(true);

        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(2);

        $entries = Entry::with('supplier')
            ->where('entry_type', 'Por compra')
            ->where('finance', false)
            ->where('created_at', '>=', $date4MonthAgo)
            ->orderBy('created_at', 'desc')
            ->get();
        /*$entries = Entry::with('supplier')->with(['details' => function ($query) {
                $query->with('material')->with(['items' => function ($query) {
                    $query->where('state_item', 'entered')
                        ->with('typescrap')
                        ->with(['location' => function ($query) {
                            $query->with(['area', 'warehouse', 'shelf', 'level', 'container']);
                        }]);
                }]);
            }])
            ->where('entry_type', 'Por compra')
            ->where('finance', false)
            ->orderBy('created_at', 'desc')
            ->get();*/
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener ingresos por compra ',
            'time' => $end
        ]);
        //dd(datatables($entries)->toJson());
        return datatables($entries)->toJson();
    }

    public function getJsonEntriesScrap()
    {
        /*$entries = Entry::where('entry_type', 'Retacería')
            ->orderBy('created_at', 'desc')
            ->get();*/
        $begin = microtime(true);
        $entries = Entry::with(['details' => function ($query) {
            $query->with('material')->with(['items' => function ($query) {
                //$query->where('state_item', 'scraped');
            }]);
        }])
            ->where('entry_type', 'Retacería')
            ->orderBy('created_at', 'desc')
            ->get();
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener ingresos por retacería',
            'time' => $end
        ]);
        //dd(datatables($entries)->toJson());
        return datatables($entries)->toJson();
    }

    public function getEntriesPurchase()
    {
        $begin = microtime(true);

        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(2);

        $entries = Entry::with('supplier')->with(['details' => function ($query) {
            $query->with('material')->with(['items' => function ($query) {
                $query->where('state_item', 'entered')
                    ->with('typescrap')
                    ->with(['location' => function ($query) {
                        $query->with(['area', 'warehouse', 'shelf', 'level', 'container']);
                    }]);
            }]);
        }])
            ->where('entry_type', 'Por compra')
            ->where('finance', false)
            ->where('created_at', '>=', $date4MonthAgo)
            ->orderBy('created_at', 'desc')
            ->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Ingresos por Compra',
            'time' => $end
        ]);
        //dd(datatables($entries)->toJson());
        return $entries;
    }

    public function destroyDetailOfEntry( $id_detail, $id_entry )
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $entry = Entry::find($id_entry);
            $detail = DetailEntry::find($id_detail);

            $items = Item::where('detail_entry_id', $detail->id)
                ->whereIn('state_item', ['reserved','exited'])
                ->get();
            if (!isset($items))
            {
                return response()->json(['message' => 'Lo sentimos, no se puede eliminar porque hay items reservados o en salida.'], 422);
            }

            $items_deleted = Item::where('detail_entry_id', $detail->id)->get();
            foreach ( $items_deleted as $item )
            {
                $material = Material::find($item->material_id);
                $material->stock_current = $material->stock_current - $item->percentage;
                $material->save();

                $item->delete();
            }

            $detail->delete();

            // TODO: Modificar el total del credito
            $credit = SupplierCredit::where('entry_id', $entry->id)->first();

            if( isset($credit) )
            {
                $credit->total_soles = ($entry->currency_invoice == 'PEN') ? (float)$entry->total:null;
                $credit->total_dollars = ($entry->currency_invoice == 'USD') ? (float)$entry->total:null;
                $credit->save();
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar detalle de ingreso',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Detalle de compra eliminado con éxito.'], 200);
    }

    public function addDetailOfEntry( Request $request, $id_entry )
    {
        $begin = microtime(true);
        //dump($request);
        DB::beginTransaction();
        try {
            $entry = Entry::find($id_entry);

            $items = json_decode($request->get('items'));

            //dd($item->id);
            $materials_id = [];

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                array_push($materials_id, $items[$i]->id_material);
            }

            $counter = array_count_values($materials_id);
            //dd($counter);

            foreach ( $counter as $id_material => $count )
            {
                $material = Material::find($id_material);
                $material->stock_current = $material->stock_current + $count;
                $material->save();

                // TODO: ORDER_QUANTITY sera tomada de la orden de compra
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_id' => $id_material,
                    'ordered_quantity' => $count,
                    'entered_quantity' => $count,
                ]);
                //dd($id_material .' '. $count);
                $total_detail = 0;
                for ( $i=0; $i<sizeof($items); $i++ )
                {
                    if( $detail_entry->material_id == $items[$i]->id_material )
                    {
                        if ( $entry->currency_invoice === 'PEN' )
                        {
                            $precio1 = round((float)$items[$i]->price,2) / (float) $entry->currency_compra;
                            //$precio1 = (float)$items[$i]->price / (float) $entry->currency_compra;
                            $price1 = ($detail_entry->material->price > $precio1) ? $detail_entry->material->price : $precio1;
                            //$price1 = ($detail_entry->material->price > $precio1) ? $detail_entry->material->price : $precio1;
                            $materialS = Material::find($detail_entry->material_id);
                            if ( $materialS->price < $price1 )
                            {
                                $materialS->unit_price = $price1;
                                $materialS->save();

                                $detail_entry->unit_price = round((float)$items[$i]->price,2);
                                //$detail_entry->unit_price = $items[$i]->price;
                                $detail_entry->save();
                            }
                            //dd($detail_entry->material->materialType);
                            if ( isset($detail_entry->material->typeScrap) )
                            {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => (float) $detail_entry->material->typeScrap->length,
                                    'width' => (float) $detail_entry->material->typeScrap->width,
                                    'weight' => 0,
                                    'price' => round((float)$items[$i]->price,2),
                                    'percentage' => 1,
                                    'typescrap_id' => $detail_entry->material->typeScrap->id,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            } else {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => 0,
                                    'width' => 0,
                                    'weight' => 0,
                                    'price' => $items[$i]->price,
                                    'percentage' => 1,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            }
                        } else {
                            $price = ((float)$detail_entry->material->unit_price > round((float)$items[$i]->price,2)) ? $detail_entry->material->unit_price : round((float)$items[$i]->price,2);
                            //$price = ($detail_entry->material->price > (float)$items[$i]->price) ? $detail_entry->material->price : $items[$i]->price;
                            $materialS = Material::find($detail_entry->material_id);
                            if ( $materialS->price < round((float)$items[$i]->price,2) )
                            {
                                $materialS->unit_price = round((float)$items[$i]->price,2);;
                                $materialS->save();

                                $detail_entry->unit_price = $materialS->unit_price;
                                $detail_entry->save();
                            }
                            //dd($detail_entry->material->materialType);
                            if ( isset($detail_entry->material->typeScrap) )
                            {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => (float) $detail_entry->material->typeScrap->length,
                                    'width' => (float) $detail_entry->material->typeScrap->width,
                                    'weight' => 0,
                                    'price' => (float) $price,
                                    'percentage' => 1,
                                    'typescrap_id' => $detail_entry->material->typeScrap->id,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            } else {
                                $item = Item::create([
                                    'detail_entry_id' => $detail_entry->id,
                                    'material_id' => $detail_entry->material_id,
                                    'code' => $items[$i]->item,
                                    'length' => 0,
                                    'width' => 0,
                                    'weight' => 0,
                                    'price' => $price,
                                    'percentage' => 1,
                                    'location_id' => $items[$i]->id_location,
                                    'state' => $items[$i]->state,
                                    'state_item' => 'entered'
                                ]);
                                $total_detail = $total_detail + (float)$items[$i]->price;
                            }
                        }


                    }
                }
                $detail_entry->total_detail = round($total_detail,2);
                $detail_entry->save();
            }

            $credit = SupplierCredit::where('entry_id', $entry->id)->first();

            if( isset($credit) )
            {
                $credit->total_soles = ($entry->currency_invoice == 'PEN') ? (float)$entry->total:null;
                $credit->total_dollars = ($entry->currency_invoice == 'USD') ? (float)$entry->total:null;
                $credit->save();
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Agregar detalle de ingreso',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Detalles de compra guardados con éxito.'], 200);
    }

    public function getAllOrders()
    {
        $begin = microtime(true);

        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(5);
        $estado = 1;
        $orders = OrderPurchase::with(['supplier', 'approved_user'])
            ->orderBy('created_at', 'desc')
            ->get();
            /*->filter(function ($orden) use ($estado) {
                return $orden->getStatusAttribute() != $estado;
            });*/
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener ordenes de compra',
            'time' => $end
        ]);
        return datatables($orders)->toJson();
    }

    public function listOrderPurchase()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('entry.listOrderPurchase', compact('permissions'));

    }

    public function createEntryOrder($id)
    {
        $begin = microtime(true);
        $suppliers = Supplier::all();
        $orderPurchase = OrderPurchase::
            with(['details' => function ($query) {
                $query->with(['material']);
            }])
            ->with('supplier')->find($id);
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear ingreso de Orden de compra',
            'time' => $end
        ]);
        return view('entry.create_entry_purchase_order', compact('suppliers', 'orderPurchase'));
    }

    public function storeEntryPurchaseOrder(StoreEntryPurchaseOrderRequest $request)
    {
        $begin = microtime(true);
        //$extension = $request->file('image')->getClientOriginalExtension();
        //dd($extension);

        //dd($request->get('deferred_invoice'));
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $orderPurchase = OrderPurchase::find($request->get('purchase_order_id'));
            $orderPurchase->status_order = 'pick_up';
            $orderPurchase->save();
            //dump($tipoCambioSunat->compra);
            $entry = Entry::create([
                'referral_guide' => $request->get('referral_guide'),
                'purchase_order' => $request->get('purchase_order'),
                'invoice' => $request->get('invoice'),
                'deferred_invoice' => ($request->has('deferred_invoice')) ? $request->get('deferred_invoice'):'off',
                'currency_invoice' => $orderPurchase->currency_order,
                'supplier_id' => $orderPurchase->supplier_id,
                'entry_type' => 'Por compra',
                'date_entry' => Carbon::createFromFormat('d/m/Y', $request->get('date_invoice')),
                'finance' => false,
                'currency_compra' => (float) $orderPurchase->currency_compra,
                'currency_venta' => (float) $orderPurchase->currency_venta,
                'observation' => $request->get('observation'),
            ]);

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $entry->image = 'no_image.png';
                $entry->save();
            } else {
                $path = public_path().'/images/entries/';
                $image = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                //dd(  );
                if ( strtoupper($extension) != "PDF")
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('image')->move($path, $filename);
                    $entry->image = $filename;
                    $entry->save();
                }
                /*$filename = $entry->id . '.jpg';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'jpg');
                //$request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();*/
            }

            if (!$request->file('imageOb')) {
                $entry->imageOb = 'no_image.png';
                $entry->save();
            } else {
                $path = public_path().'/images/entries/observations/';
                $image = $request->file('imageOb');
                $extension = $image->getClientOriginalExtension();
                if ( strtoupper($extension) != "PDF" )
                {
                    $filename = $entry->id . '.JPG';
                    $img = Image::make($image);
                    $img->orientate();
                    $img->save($path.$filename, 80, 'JPG');
                    //$request->file('image')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                } else {
                    $filename = 'pdf'.$entry->id . '.' .$extension;
                    $request->file('imageOb')->move($path, $filename);
                    $entry->imageOb = $filename;
                    $entry->save();
                }
                /*$filename = $entry->id . '.jpg';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'jpg');
                $entry->imageOb = $filename;
                $entry->save();*/
            }

            $items = json_decode($request->get('items'));

            //dd($items);

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                //dd($items[$i]->id);
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_id' => $items[$i]->id,
                    'ordered_quantity' => $items[$i]->quantity,
                    'entered_quantity' => $items[$i]->entered,
                    'isComplete' => ($items[$i]->quantity == $items[$i]->entered) ? true:false,
                ]);

                // TODO: Revisamos si hay un material en seguimiento y creamos
                // TODO: la notificacion y cambiamos el estado
                $follows = FollowMaterial::where('material_id', $detail_entry->material->id)
                    ->get();
                if ( !$follows->isEmpty() )
                {
                    // TODO: Creamos notificacion y cambiamos el estado
                    // Crear notificacion
                    $notification = Notification::create([
                        'content' => 'El material ' . $detail_entry->material->full_description . ' ha sido ingresado.',
                        'reason_for_creation' => 'follow_material',
                        'user_id' => Auth::user()->id,
                        'url_go' => route('follow.index')
                    ]);

                    // Roles adecuados para recibir esta notificación admin, logistica
                    $users = User::role(['admin', 'operator'])->get();
                    foreach ( $users as $user )
                    {
                        $followUsers = FollowMaterial::where('material_id', $detail_entry->material_id)
                            ->where('user_id', $user->id)
                            ->get();
                        if ( !$followUsers->isEmpty() )
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
                    foreach ( $follows as $follow )
                    {
                        $follow->state = 'in_warehouse';
                        $follow->save();
                    }
                }

                $orderPurchasesDetail = OrderPurchaseDetail::where('order_purchase_id', $orderPurchase->id)
                    ->where('material_id', $items[$i]->id)
                    ->first();
                $materialOrder = MaterialOrder::where('order_purchase_detail_id',$orderPurchasesDetail->id)
                    ->where('material_id',$orderPurchasesDetail->material_id)->first();

                $materialOrder->quantity_entered = $items[$i]->entered;
                $materialOrder->save();

                $material = Material::find($detail_entry->material_id);
                $material->stock_current = $material->stock_current + $detail_entry->entered_quantity;
                $material->save();

                if ( $entry->currency_invoice === 'PEN' )
                {
                    $precio1 = (float)$items[$i]->price / (float) $entry->currency_compra;
                    $price1 = ($detail_entry->material->price > $precio1) ? $detail_entry->material->price : $precio1;
                    $materialS = Material::find($detail_entry->material_id);
                    if ( $materialS->price < $price1 )
                    {
                        $materialS->unit_price = $price1;
                        $materialS->save();

                        $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                        $detail_entry->save();
                    } else {
                        $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                        $detail_entry->save();
                    }
                    //dd($detail_entry->material->materialType);
                    if ( isset($detail_entry->material->typeScrap) )
                    {
                        for ( $k=0; $k<(int)$detail_entry->entered_quantity; $k++ )
                        {
                            Item::create([
                                'detail_entry_id' => $detail_entry->id,
                                'material_id' => $detail_entry->material_id,
                                'code' => $this->generateRandomString(20),
                                'length' => (float)$detail_entry->material->typeScrap->length,
                                'width' => (float)$detail_entry->material->typeScrap->width,
                                'weight' => 0,
                                'price' => (float)$items[$i]->price,
                                'percentage' => 1,
                                'typescrap_id' => $detail_entry->material->typeScrap->id,
                                'location_id' => ($items[$i]->id_location)=='' ? 1:$items[$i]->id_location,
                                'state' => 'good',
                                'state_item' => 'entered'
                            ]);
                        }

                    } else {
                        for ( $k=0; $k<(int)$detail_entry->entered_quantity; $k++ )
                        {
                            Item::create([
                                'detail_entry_id' => $detail_entry->id,
                                'material_id' => $detail_entry->material_id,
                                'code' => $this->generateRandomString(20),
                                'length' => 0,
                                'width' => 0,
                                'weight' => 0,
                                'price' => (float)$items[$i]->price,
                                'percentage' => 1,
                                'location_id' => ($items[$i]->id_location) == '' ? 1 : $items[$i]->id_location,
                                'state' => 'good',
                                'state_item' => 'entered'
                            ]);
                        }
                    }
                } else {
                    $price = ((float)$detail_entry->material->unit_price > (float)$items[$i]->price) ? $detail_entry->material->unit_price : $items[$i]->price;
                    //dump($detail_entry->material->unit_price);
                    //dump((float)$items[$i]->price);
                    //dd($price);
                    $materialS = Material::find($detail_entry->material_id);
                    if ( (float)$materialS->unit_price < (float)$price )
                    {
                        $materialS->unit_price = (float)$price;
                        $materialS->save();

                        $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                        $detail_entry->save();
                    } else {
                        $detail_entry->unit_price = (float) round((float)$items[$i]->price,2);
                        $detail_entry->save();
                    }
                    //dd($detail_entry->material->materialType);
                    if ( isset($detail_entry->material->typeScrap) )
                    {
                        for ( $k=0; $k<(int)$detail_entry->entered_quantity; $k++ )
                        {
                            Item::create([
                                'detail_entry_id' => $detail_entry->id,
                                'material_id' => $detail_entry->material_id,
                                'code' => $this->generateRandomString(20),
                                'length' => (float)$detail_entry->material->typeScrap->length,
                                'width' => (float)$detail_entry->material->typeScrap->width,
                                'weight' => 0,
                                'price' => (float)$price,
                                'percentage' => 1,
                                'typescrap_id' => $detail_entry->material->typeScrap->id,
                                'location_id' => ($items[$i]->id_location) == '' ? 1 : $items[$i]->id_location,
                                'state' => 'good',
                                'state_item' => 'entered'
                            ]);
                        }
                    } else {
                        //dd($detail_entry->material->typeScrap);
                        for ( $k=0; $k<(int)$detail_entry->entered_quantity; $k++ )
                        {
                            Item::create([
                                'detail_entry_id' => $detail_entry->id,
                                'material_id' => $detail_entry->material_id,
                                'code' => $this->generateRandomString(20),
                                'length' => 0,
                                'width' => 0,
                                'weight' => 0,
                                'price' => (float)$price,
                                'percentage' => 1,
                                'location_id' => ($items[$i]->id_location) == '' ? 1 : $items[$i]->id_location,
                                'state' => 'good',
                                'state_item' => 'entered'
                            ]);
                        }
                    }
                }

            }


            /* SI ( En el campo factura y en (Orden Compra/Servicio) ) AND Diferente a 000
                Entonces
                SI ( Existe en la tabla creditos ) ENTONCES
                actualiza la factura en la tabla de creditos
            */
            //dd($entry->invoice);
            if ( ($entry->invoice != '' || $entry->invoice != null) )
            {
                //dd($entry->purchase_order);
                if ( $entry->purchase_order != '' || $entry->purchase_order != null )
                {
                    $order_purchase = OrderPurchase::where('code', $entry->purchase_order)->first();

                    if ( isset($order_purchase) )
                    {
                        /*if ( $credit->invoice != "" || $credit->invoice != null )
                        {
                            //$credit->delete();
                            $deadline = PaymentDeadline::find($credit->deadline->id);
                            $fecha_issue = Carbon::parse($entry->date_entry);
                            $fecha_expiration = $fecha_issue->addDays($deadline->days);
                            // TODO: Poner dias
                            $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                            $credit->supplier_id = $entry->supplier_id;
                            $credit->invoice = $credit->invoice.' | '.$entry->invoice;
                            $credit->image_invoice = $entry->image;
                            $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                            $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                            $credit->date_issue = $entry->date_entry;
                            $credit->date_expiration = $fecha_expiration;
                            $credit->days_to_expiration = $dias_to_expire;
                            $credit->code_order = $entry->purchase_order;
                            $credit->save();
                        } else {
                            //$credit->delete();
                            $deadline = PaymentDeadline::find($credit->deadline->id);
                            $fecha_issue = Carbon::parse($entry->date_entry);
                            $fecha_expiration = $fecha_issue->addDays($deadline->days);
                            // TODO: Poner dias
                            $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));
                            $credit->supplier_id = $entry->supplier_id;
                            $credit->invoice = $entry->invoice;
                            $credit->image_invoice = $entry->image;
                            $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                            $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                            $credit->date_issue = $entry->date_entry;
                            $credit->date_expiration = $fecha_expiration;
                            $credit->days_to_expiration = $dias_to_expire;
                            $credit->code_order = $entry->purchase_order;
                            $credit->save();
                        }*/
                        if ( isset($order_purchase->deadline) )
                        {
                            if ( $order_purchase->deadline->credit == 1 || $order_purchase->deadline->credit == true )
                            {
                                $deadline = PaymentDeadline::find($order_purchase->deadline->id);
                                $fecha_issue = Carbon::parse($entry->date_entry);
                                $fecha_expiration = $fecha_issue->addDays($deadline->days);
                                // TODO: Poner dias
                                $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                                $credit = SupplierCredit::create([
                                    'supplier_id' => $order_purchase->supplier->id,
                                    'invoice' => ($this->onlyZeros($entry->invoice) == true ) ? null:$entry->invoice,
                                    'image_invoice' => $entry->image,
                                    'total_soles' => ($order_purchase->currency_order == 'PEN') ? (float)$entry->total:null,
                                    'total_dollars' => ($order_purchase->currency_order == 'USD') ? (float)$entry->total:null,
                                    'date_issue' => Carbon::parse($entry->date_entry),
                                    'order_purchase_id' => $order_purchase->id,
                                    'state_credit' => 'outstanding',
                                    'order_service_id' => null,
                                    'date_expiration' => $fecha_expiration,
                                    'days_to_expiration' => $dias_to_expire,
                                    'code_order' => $order_purchase->code,
                                    'payment_deadline_id' => $order_purchase->payment_deadline_id,
                                    'entry_id' => $entry->id
                                ]);
                            }
                        }

                    }
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar ingreso de Orden de compra',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por compra guardado con éxito.', 'url'=>route('entry.purchase.index')], 200);

    }

    function generateRandomString($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getOrderPurchaseComplete($order)
    {
        $entry = Entry::where('purchase_order', $order)
            ->get();

        $order_purchase = OrderPurchase::where('code', $order)->first();

        if ( count($entry) > 0 )
        {
            $details = OrderPurchaseDetail::where('order_purchase_id', $order_purchase->id)->get();

            if (isset($details))
            {
                foreach ($details as $detail)
                {
                    $material = $detail->material_id;
                    // TODO: obtener las entradas de esa orden y material
                    $cant_material = 0;
                    foreach ( $entry as $entrada )
                    {
                        $entry_details_sum = DetailEntry::where('entry_id', $entrada->id)
                            ->where('material_id', $material)->sum('entered_quantity');
                        $cant_material += $entry_details_sum;
                    }


                    if ($cant_material < $detail->quantity)
                    {
                        // TODO: Esto significa que esta incompleta
                        return 0;
                    }
                }
                // TODO: Esto significa que esta completa
                return 1;
            }
            // TODO: Esto significa que esta por ingresar
            return 2;
        }
        // TODO: Esto significa que esta por ingresar
        return 2;
        /*if ( isset($order) )
        {
            $details = DetailEntry::where('entry_id', $order->id)->get();
            if (isset($details))
            {
                foreach ($details as $detail)
                {
                    if ($detail->isComplete == false)
                    {
                        return 0;
                    }
                }
                return 1;
            }
            return 2;
        }
        return 2;*/

    }

    public function regularizeAutoOrderEntryPurchase( $entry_id )
    {
        $begin = microtime(true);
        $entry = Entry::find($entry_id);
        $details = DetailEntry::where('entry_id', $entry_id)->get();
        //dd($entry);

        $suppliers = Supplier::all();

        $users = User::all();

        // TODO: WITH TRASHED
        $maxCode = OrderPurchase::withTrashed()->max('id');
        $maxId = $maxCode + 1;
        //$maxCode = OrderPurchase::max('code');
        //$maxId = (int)substr($maxCode,3) + 1;
        $length = 5;
        $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

        $payment_deadlines = PaymentDeadline::where('type', 'purchases')->get();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'AutoRegularizar Vista de orden de compra',
            'time' => $end
        ]);
        return view('orderPurchase.regularizeEntryPurchase', compact('entry', 'details', 'suppliers', 'users', 'codeOrder', 'payment_deadlines'));

    }

    public function regularizeEntryToOrderPurchase(StoreOrderPurchaseRequest $request)
    {
        $begin = microtime(true);
        //dd($request);
        $validated = $request->validated();

        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        //dump($request->get('date_invoice'));
        $fecha = ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now();
        //$fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_order'));

        //dump();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha->format('Y-m-d'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $tipoCambioSunat = json_decode($response);

        DB::beginTransaction();
        try {
            $maxCode = OrderPurchase::withTrashed()->max('id');
            $maxId = $maxCode + 1;
            $length = 5;
            //$codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);

            $orderPurchase = OrderPurchase::create([
                'code' => '',
                'quote_supplier' => $request->get('quote_supplier'),
                'payment_deadline_id' => ($request->has('payment_deadline_id')) ? $request->get('payment_deadline_id') : null,
                'supplier_id' => ($request->has('supplier_id')) ? $request->get('supplier_id') : null,
                'date_arrival' => ($request->has('date_arrival')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_arrival')) : Carbon::now(),
                'date_order' => ($request->has('date_order')) ? Carbon::createFromFormat('d/m/Y', $request->get('date_order')) : Carbon::now(),
                'approved_by' => ($request->has('approved_by')) ? $request->get('approved_by') : null,
                'payment_condition' => ($request->has('purchase_condition')) ? $request->get('purchase_condition') : '',
                'currency_order' => ($request->get('state') === 'true') ? 'PEN': 'USD',
                'currency_compra' => $tipoCambioSunat->compra,
                'currency_venta' => $tipoCambioSunat->venta,
                'observation' => $request->get('observation'),
                'igv' => $request->get('taxes_send'),
                'total' => $request->get('total_send'),
                'type' => 'n',
                'regularize' => ($request->get('regularize') === 'true') ? 'r': 'nr',
                'status_order' => 'pick_up'
            ]);

            $codeOrder = '';
            if ( $maxId < $orderPurchase->id ){
                $codeOrder = 'OC-'.str_pad($orderPurchase->id,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            } else {
                $codeOrder = 'OC-'.str_pad($maxId,$length,"0", STR_PAD_LEFT);
                $orderPurchase->code = $codeOrder;
                $orderPurchase->save();
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $orderPurchaseDetail = OrderPurchaseDetail::create([
                    'order_purchase_id' => $orderPurchase->id,
                    'material_id' => $items[$i]->id_material,
                    'quantity' => (float) $items[$i]->quantity,
                    'price' => (float) $items[$i]->price,
                    'total_detail' => (float) $items[$i]->total,
                ]);

                // TODO: Revisamos si hay un material en seguimiento y creamos
                // TODO: la notificacion y cambiamos el estado
                $follows = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                    ->get();
                if ( !$follows->isEmpty() )
                {
                    // TODO: Creamos notificacion y cambiamos el estado
                    // Crear notificacion
                    $notification = Notification::create([
                        'content' => 'El material ' . $orderPurchaseDetail->material->full_description . ' ha sido pedido.',
                        'reason_for_creation' => 'follow_material',
                        'user_id' => Auth::user()->id,
                        'url_go' => route('follow.index')
                    ]);

                    // Roles adecuados para recibir esta notificación admin, logistica
                    $users = User::role(['admin', 'operator'])->get();
                    foreach ( $users as $user )
                    {
                        $followUsers = FollowMaterial::where('material_id', $orderPurchaseDetail->material_id)
                            ->where('user_id', $user->id)
                            ->get();
                        if ( !$followUsers->isEmpty() )
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
                    foreach ( $follows as $follow )
                    {
                        $follow->state = 'in_order';
                        $follow->save();
                    }
                }

                $total = $orderPurchaseDetail->total_detail;
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                $orderPurchaseDetail->igv = $igv;
                $orderPurchaseDetail->save();

                MaterialOrder::create([
                    'order_purchase_detail_id' => $orderPurchaseDetail->id,
                    'material_id' => $orderPurchaseDetail->material_id,
                    'quantity_request' => $orderPurchaseDetail->quantity,
                    'quantity_entered' => $orderPurchaseDetail->quantity
                ]);
            }

            // Si el plazo indica credito, se crea el credito


            // TODO: Actualizamos la entrada
            $entry = Entry::find($request->get('entry_id'));
            $entry->purchase_order = $orderPurchase->code;
            $entry->save();

            if ( isset($orderPurchase->deadline) )
            {
                if ( $orderPurchase->deadline->credit == 1 || $orderPurchase->deadline->credit == true )
                {
                    $deadline = PaymentDeadline::find($orderPurchase->deadline->id);
                    $fecha_issue = Carbon::parse($entry->date_entry);
                    $fecha_expiration = $fecha_issue->addDays($deadline->days);
                    // TODO: Poner dias
                    $dias_to_expire = $fecha_expiration->diffInDays(Carbon::now('America/Lima'));

                    $credit = SupplierCredit::create([
                        'supplier_id' => $orderPurchase->supplier->id,
                        'invoice' => ($this->onlyZeros($entry->invoice)) ? null:$entry->invoice,
                        'image_invoice' => $entry->image,
                        'total_soles' => ($orderPurchase->currency_order == 'PEN') ? $entry->total:null,
                        'total_dollars' => ($orderPurchase->currency_order == 'USD') ? $entry->total:null,
                        'date_issue' => $entry->date_entry,
                        'order_purchase_id' => $orderPurchase->id,
                        'state_credit' => 'outstanding',
                        'order_service_id' => null,
                        'date_expiration' => $fecha_expiration,
                        'days_to_expiration' => $dias_to_expire,
                        'code_order' => $orderPurchase->code,
                        'payment_deadline_id' => $orderPurchase->payment_deadline_id
                    ]);
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'AutoRegularizar Post de orden de compra',
                'time' => $end
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Su orden de compra con el código '.$codeOrder.' se guardó con éxito.', 'url'=>route('invoice.index')], 200);

    }

    public function reportMaterialEntries()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('entry.reportMaterialEntries', compact('permissions'));
    }

    public function getJsonMaterialsInEntry()
    {
        $begin = microtime(true);
        $materials = Material::where('enable_status', 1)->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'code' => $material->code]);
        }
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener lista de materiales que tienen ingreso',
            'time' => $end
        ]);

        return $array;
    }

    public function getJsonEntriesOfMaterial( $id_material )
    {
        $begin = microtime(true);

        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(4);

        $entryDetails = DetailEntry::where('material_id', '=', $id_material)
            //->where('created_at', '>=', $date4MonthAgo)
            //->where('entry_type', 'Por compra')
            ->get();
        $entries = [];
        foreach ($entryDetails as $entryDetail) {
            $entry = Entry::with(['supplier'])->find($entryDetail->entry_id);
            if ( $entry->entry_type != 'Retacería' )
            {
                array_push($entries, [
                    'entry' => $entry->id,
                    'guide' => $entry->referral_guide,
                    'order' => $entry->purchase_order,
                    'invoice' => $entry->invoice,
                    'supplier' => ( $entry->supplier == null ) ? 'Sin proveedor':$entry->supplier->business_name,
                    'date' => $entry->date_entry,
                    'quantity' => (float)$entryDetail->entered_quantity,
                ]);
            }

        }

        $new_arr2 = array();
        foreach($entries as $item) {
            if(isset($new_arr2[$item['entry']])) {
                $new_arr2[ $item['entry']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr2[$item['entry']] = $item;
        }

        $centries_final = array_values($new_arr2);
        //dump($outputs);
        //$result = array_values( array_unique($outputs) );
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener ingresos de materiales',
            'time' => $end
        ]);
        return $centries_final;
    }

    public function showExtraDocumentEntryPurchase( $entry_id )
    {
        $begin = microtime(true);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $entry = Entry::find($entry_id);

        $imagesInvoices = [];
        $imagesGuides = [];
        $imagesObservations = [];

        $imagenesFactura = EntryImage::where('entry_id', $entry->id)
            ->where('type', 'i')->get();
        $imagenesGuias = EntryImage::where('entry_id', $entry->id)
            ->where('type', 'g')->get();
        $imagenesObs = EntryImage::where('entry_id', $entry->id)
            ->where('type', 'o')->get();

        if ($imagenesFactura->count() > 0)
        {
            $imagesInvoices = $imagenesFactura;
        }
        if ($imagenesGuias->count() > 0)
        {
            $imagesGuides = $imagenesGuias;
        }
        if ($imagenesObs->count() > 0)
        {
            $imagesObservations = $imagenesObs;
        }
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Ver imagenes extras de entradas',
            'time' => $end
        ]);
        //dump($quote);
        return view('entry.editImages', compact('entry','permissions', 'imagesInvoices', 'imagesGuides', 'imagesObservations'));

    }

    public function updateImage(Request $request, $image)
    {
        $begin = microtime(true);
        //dd($request->get('image_id'));
        DB::beginTransaction();
        try {
            $id = $request->get('image_id');
            $code = $request->get('image_code');

            $image = EntryImage::find($id);
            $image->code = $code;
            $image->save();

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Imagen Entrada modificada ',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Imagen modificada con éxito'], 200);

    }

    public function deleteImage(Request $request, $image)
    {
        //dd($request->get('image_id'));
        DB::beginTransaction();
        try {
            $id = $request->get('image_id');

            $imagen = EntryImage::find($id);

            $image_path = public_path().'/images/entries/extras/'.$imagen->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $imagen->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Imagen eliminada con éxito'], 200);

    }

    public function saveImages(Request $request, $entry_id)
    {
        $begin = microtime(true);
        //dd($request->get('image_id'));
        DB::beginTransaction();
        try {
            // TODO: Tratamiento de las imagenes de las entradas

            $images = $request->images;
            $descriptions = $request->codeimages;
            $types = $request->types;

            if ( count($images) != 0 && count($descriptions) != 0 )
            {
                foreach ( $images as $key => $image )
                {
                    $path = public_path().'/images/entries/extras/';
                    $img = $image;

                    $extension = $img->getClientOriginalExtension();
                    //$filename = $entry->id . '.' . $extension;
                    if ( strtoupper($extension) != "PDF" )
                    {
                        $filename = $entry_id .'_'. $this->generateRandomString(20). '.JPG';
                        $imgQuote = Image::make($img);
                        $imgQuote->orientate();
                        $imgQuote->save($path.$filename, 80, 'JPG');

                        EntryImage::create([
                            'entry_id' => $entry_id,
                            'code' => $descriptions[$key],
                            'image' => $filename,
                            'type' => $types[$key],
                            'type_file' => 'img',
                        ]);
                    } else {
                        $filename = 'pdf'.$entry_id .'_'. $this->generateRandomString(20) . '.' .$extension;
                        $img->move($path, $filename);

                        EntryImage::create([
                            'entry_id' => $entry_id,
                            'code' => $descriptions[$key],
                            'image' => $filename,
                            'type' => $types[$key],
                            'type_file' => 'pdf'
                        ]);
                    }

                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar images entries POST',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Imágenes guardadas con éxito'], 200);

    }

    public function onlyZeros($cadena) {
        $cadenaSinGuiones = str_replace('-', '', $cadena); // Eliminar los guiones

        if (!ctype_digit($cadenaSinGuiones)) {
            return false; // La cadena contiene caracteres que no son dígitos
        }

        if ($cadenaSinGuiones !== str_repeat('0', strlen($cadenaSinGuiones))) {
            return false; // La cadena no está formada solo por ceros
        }

        return true; // La cadena está formada solo por ceros
    }
}
