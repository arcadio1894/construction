<?php

namespace App\Http\Controllers;

use App\DetailEntry;
use App\Entry;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Item;
use App\Material;
use App\OrderService;
use App\PaymentDeadline;
use App\Supplier;
use App\SupplierCredit;
use App\UnitMeasure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class InvoiceController extends Controller
{
    public function indexInvoices()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('invoice.index_invoice', compact('permissions'));
    }

    public function createInvoice()
    {
        $suppliers = Supplier::all();
        $unitMeasures = UnitMeasure::all();
        return view('invoice.create_invoice', compact('suppliers', 'unitMeasures'));
    }

    public function storeInvoice(StoreInvoiceRequest $request)
    {
        //dd($request->get('deferred_invoice'));
        $validated = $request->validated();

        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_invoice'));

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
            $entry = Entry::create([
                'purchase_order' => $request->get('purchase_order'),
                'invoice' => $request->get('invoice'),
                'deferred_invoice' => ($request->has('deferred_invoice')) ? $request->get('deferred_invoice'):'off',
                'supplier_id' => $request->get('supplier_id'),
                'entry_type' => $request->get('entry_type'),
                'type_order' => $request->get('type_order'),
                'date_entry' => Carbon::createFromFormat('d/m/Y', $request->get('date_invoice')),
                'finance' => true,
                'currency_invoice' => ($request->has('currency_invoice')) ? 'USD':'PEN',
                'currency_compra' => (float) $tipoCambioSunat->compra,
                'currency_venta' => (float) $tipoCambioSunat->venta
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
                /*$path = public_path().'/images/entries/';
                $image = $request->file('image');
                $filename = $entry->id . '.JPG';
                $img = Image::make($image);
                $img->orientate();
                $img->save($path.$filename, 80, 'JPG');
                //$request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();*/
                /*$extension = $request->file('image')->getClientOriginalExtension();
                $filename = $entry->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();*/
            }

            $items = json_decode($request->get('items'));

            for ( $i=0; $i<sizeof($items); $i++ )
            {
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_name' => $items[$i]->material,
                    'ordered_quantity' => $items[$i]->quantity,
                    'entered_quantity' => $items[$i]->quantity,
                    'unit_price' => (float) round((float)$items[$i]->price,2),
                    'material_unit' => $items[$i]->unit,
                    'total_detail' => (float) $items[$i]->total
                ]);
            }

            /*$items = json_decode($request->get('items'));

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
                for ( $i=0; $i<sizeof($items); $i++ )
                {
                    if( $detail_entry->material_id == $items[$i]->id_material )
                    {
                        $price = ($detail_entry->material->price > (float)$items[$i]->price) ? $detail_entry->material->price : $items[$i]->price;
                        $materialS = Material::find($detail_entry->material_id);
                        if ( $materialS->price < $items[$i]->price )
                        {
                            $materialS->unit_price = $items[$i]->price;
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
                                'length' => $detail_entry->material->typeScrap->length,
                                'width' => $detail_entry->material->typeScrap->width,
                                'weight' => 0,
                                'price' => $price,
                                'percentage' => 1,
                                'typescrap_id' => $detail_entry->material->typeScrap->id,
                                'location_id' => $items[$i]->id_location,
                                'state' => $items[$i]->state,
                                'state_item' => 'entered'
                            ]);
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
                        }

                    }
                }
            }*/

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
                        ->where('code_order', $entry->purchase_order)
                        ->where('state_credit', 'outstanding')->first();

                    if ( isset($credit) )
                    {
                        //$credit->delete();
                        $deadline = PaymentDeadline::find($credit->deadline->id);
                        $fecha_issue = Carbon::parse($entry->date_entry);
                        $fecha_expiration = $fecha_issue->addDays($deadline->days);
                        $credit->supplier_id = $entry->date_entry;
                        $credit->invoice = $entry->invoice;
                        $credit->image_invoice = $entry->image;
                        $credit->total_soles = ((float)$credit->total_soles>0) ? $entry->total:null;
                        $credit->total_dollars = ((float)$credit->total_dollars>0) ? $entry->total:null;
                        $credit->date_issue = $entry->date_entry;
                        $credit->days_to_expiration = $fecha_expiration;
                        $credit->code_order = $entry->purchase_order;
                        $credit->save();

                    }
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Factura por compra/servicio guardada con éxito.'], 200);

    }

    public function getJsonInvoices()
    {
        $entries = Entry::with('supplier')
            ->with(['details' => function ($query) {
                $query->with('material');
            }])
            ->where('entry_type', 'Por compra')
            ->orderBy('created_at', 'desc')
            ->get();
        $orderServices = OrderService::with('supplier')
            ->with(['details'])
            ->where('regularize', 'r')
            ->orderBy('created_at', 'desc')
            ->get();
        $array = [];
        foreach ( $entries as $entry )
        {
            array_push($array, $entry);
        }
        foreach ( $orderServices as $orderService )
        {
            array_push($array, $orderService);
        }
        //dd(datatables($entries)->toJson());
        return datatables($array)->toJson();
    }

    public function getInvoiceById( $id )
    {
        $entry = Entry::with('supplier')->with(['details' => function ($query) {
            $query->with('material');
        }])
            ->where('id', $id)
            ->get();
        return json_encode($entry);

    }

    public function getServiceById( $id )
    {
        /*$entry = Entry::with('supplier')->with(['details' => function ($query) {
            $query->with('material');
        }])
            ->where('id', $id)
            ->get();*/

        $service = OrderService::with('supplier')->with('details')
            ->where('id', $id)
            ->get();
        return json_encode($service);

    }

    public function getInvoices()
    {
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
            ->orderBy('created_at', 'desc')
            ->get();

        //dd(datatables($entries)->toJson());
        return $entries;
    }

    public function editInvoice(Entry $entry)
    {
        $suppliers = Supplier::all();
        return view('invoice.edit_invoice', compact('entry', 'suppliers'));
    }

    public function updateInvoice(UpdateInvoiceRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $entry = Entry::find($request->get('entry_id'));
            $entry->purchase_order = $request->get('purchase_order');
            $entry->invoice = $request->get('invoice');
            $entry->deferred_invoice = ($request->get('deferred_invoice') === 'true') ? 'on': 'off';
            $entry->supplier_id = $request->get('supplier_id');
            $entry->date_entry = Carbon::createFromFormat('d/m/Y', $request->get('date_invoice'));
            $entry->type_order = $request->get('type_order');
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
                /*$extension = $request->file('image')->getClientOriginalExtension();
                $filename = $entry->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();*/
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Factura por compra/servicio modificada con éxito.'], 200);

    }


}
