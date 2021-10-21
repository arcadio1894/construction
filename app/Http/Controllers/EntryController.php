<?php

namespace App\Http\Controllers;

use App\DetailEntry;
use App\Entry;
use App\Http\Requests\StoreEntryPurchaseRequest;
use App\Http\Requests\UpdateEntryPurchaseRequest;
use App\Item;
use App\Material;
use App\Supplier;
use App\Typescrap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        //dd($request->get('deferred_invoice'));
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $entry = Entry::create([
                'referral_guide' => $request->get('referral_guide'),
                'purchase_order' => $request->get('purchase_order'),
                'invoice' => $request->get('invoice'),
                'deferred_invoice' => ($request->has('deferred_invoice')) ? $request->get('deferred_invoice'):'off',
                'currency_invoice' => ($request->has('currency_invoice')) ? $request->get('deferred_invoice'):'off',
                'supplier_id' => $request->get('supplier_id'),
                'entry_type' => $request->get('entry_type'),
                'date_entry' => Carbon::createFromFormat('d/m/Y', $request->get('date_invoice')),
                'finance' => false
            ]);

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $entry->image = 'no_image.png';
                $entry->save();
            } else {
                $path = public_path().'/images/entries/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $entry->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();
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
            }

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
            if( isset($material->typeScrap) && $material->typeScrap->id == 3 )
            {
                $porcentaje = ($item->length)/($material->materialType->length);
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

    public function updateEntryPurchase(UpdateEntryPurchaseRequest $request)
    {
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

            $entry->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                if ($entry->image == 'no_image.png' || $entry->image == null) {
                    $entry->image = 'no_image.png';
                    $entry->save();
                }
            } else {
                $path = public_path().'/images/entries/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $entry->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $entry->image = $filename;
                $entry->save();
            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ingreso por compra modificado con éxito.'], 200);

    }

    public function destroy(Entry $entry)
    {
        //
    }

    public function getJsonEntriesPurchase()
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
            ->where('finance', false)
            ->get();

        //dd(datatables($entries)->toJson());
        return datatables($entries)->toJson();
    }

    public function getJsonEntriesScrap()
    {
        $entries = Entry::with(['details' => function ($query) {
            $query->with('material')->with(['items' => function ($query) {
                $query->where('state_item', 'entered');
            }]);
        }])
            ->where('entry_type', 'Retacería')
            ->get();

        //dd(datatables($entries)->toJson());
        return datatables($entries)->toJson();
    }

    public function getEntriesPurchase()
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
            ->where('finance', false)
            ->get();

        //dd(datatables($entries)->toJson());
        return $entries;
    }
}
