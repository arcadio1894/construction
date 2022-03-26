<?php

namespace App\Http\Controllers;

use App\DetailEntry;
use App\Entry;
use App\Item;
use App\Material;
use App\Typescrap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntryScrapsController extends Controller
{
    public function indexScrapsMaterials()
    {
        return view('scraps.index_materials_scrap');
    }

    public function getJsonIndexMaterialsScraps()
    {
        $materials = Material::with('typeScrap:id,name,length,width')
            ->whereNotNull('typescrap_id')
            ->where('stock_current', '>', 0)
            ->where('enable_status', 1)
            ->get();

        return datatables($materials)->toJson();
    }

    public function showItemsByMaterial( $material_id )
    {
        $material = Material::find($material_id);
        return view('scraps.index_items_material', compact('material'));
    }

    public function getJsonIndexItemsMaterial($material_id)
    {
        $items = Item::with(['material'])
            ->with(['detailEntry' => function ($query) {
                $query->with(['entry']);
            }])
            ->where('material_id', $material_id)
            ->whereNotIn('state_item', ['exited'])
            ->orderBy('created_at', 'DESC')
            ->orderBy('state_item', 'ASC')
            ->get();
        //dd($items);

        return datatables($items)->toJson();
    }

    public function storeScrap( Request $request )
    {
        //dd($request);
        $material_id = (int)$request->get('material_id');
        $material = $request->get('material');
        $price = (float)$request->get('price');
        $idItem = (int)$request->get('idItem');
        $typescrap = (int)$request->get('typescrap');
        $code = $request->get('code');
        $length = (float)$request->get('length');
        $width = (float)$request->get('width');
        $length_new = ($request->get('length_new') == null) ? 0:(float)$request->get('length_new');
        $width_new = ($request->get('width_new') == null) ? 0:(float)$request->get('width_new');
        $location = ($request->get('location') == null) ? 1:$request->get('location');
        $state = $request->get('state');
        $blockAncho = (int)$request->get('blockAncho');
        $blockLargo = (int)$request->get('blockLargo');

        // TODO: CHECKAR CUADNO SACAN UNA PARTE DE TODO EL LARGO O ANCHO PORQUE QUEDA CERO
        DB::beginTransaction();
        try {

            if ( $typescrap == 1 || $typescrap == 2 )
            {
                if ( $length_new == 0 || $width_new == 0 )
                {
                    return response()->json(['message' => 'Ingrese el largo o ancho mayor a cero'], 422);
                }
            }

            if ( $typescrap == 3 )
            {
                if ( $length_new == 0 )
                {
                    return response()->json(['message' => 'Ingrese el largo mayor a cero'], 422);
                }
            }

            if ( $typescrap == 1 || $typescrap == 2 )
            {
                $materialSelected = Material::find($material_id);
                $itemSelected = Item::find($idItem);
                $typescrapSelected = Typescrap::find($typescrap);

                //TODO: Vamos a crear un item y luego modificamos el itemSelected
                //TODO: Nuevo Item
                $new_code = $this->generateRandomString(25);
                $areaComplete = round($typescrapSelected->length*$typescrapSelected->width,2);
                $areaOriginal = round($length * $width, 2);
                $areaScrap = round($length_new * $width_new, 2);
                $percentage_new = round($areaScrap/$areaComplete, 2);
                $price_new = round($price * $percentage_new, 2);

                $areaOld = 0;
                $percentage_old = 0;
                $price_old = 0;
                $length_old = 0;
                $width_old = 0;
                // TODO: Si esta blockeado el ancho
                if ( $blockAncho == 1 )
                {
                    $areaOld = round(($itemSelected->length - $length_new) * ($itemSelected->width), 2);
                    $percentage_old = round($areaOld/$areaComplete, 2);
                    $price_old = round($price * $percentage_old, 2);

                    $length_old = round($itemSelected->length - $length_new, 2);
                    $width_old = round($itemSelected->width, 2);
                }

                if ( $blockLargo == 1 )
                {
                    $areaOld = round(($itemSelected->length) * ($itemSelected->width - $width_new), 2);
                    $percentage_old = round($areaOld/$areaComplete, 2);
                    $price_old = round($price * $percentage_old, 2);

                    $length_old = round($itemSelected->length, 2);
                    $width_old = round($itemSelected->width - $width_new, 2);
                }

                if ( $itemSelected->state_item != 'exited' )
                {
                    // TODO: Restamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current-$itemSelected->percentage;
                    $materialSelected->save();
                    // TODO: Sumamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current + $percentage_old + $percentage_new;
                    $materialSelected->save();
                    // TODO: Actualizamos el item anterior
                    $itemSelected->length = $length_old ;
                    $itemSelected->width = $width_old ;
                    $itemSelected->price = $price_old ;
                    $itemSelected->percentage = $percentage_old ;
                    $itemSelected->state_item = 'scraped';
                    $itemSelected->save();
                    // TODO: Creamos la entrada y el item
                    $entry = Entry::create([
                        'entry_type' => "Retacería",
                        'date_entry' => Carbon::now(),
                        'finance' => false
                    ]);
                    $detail_entry = DetailEntry::create([
                        'entry_id' => $entry->id,
                        'material_id' => $material_id,
                    ]);

                    $itemNuevo = Item::create([
                        'detail_entry_id' => $detail_entry->id,
                        'material_id' => $materialSelected->id,
                        'code' => $new_code,
                        'length' => $length_new,
                        'width' => $width_new,
                        'weight' => 0,
                        'price' => $price_new,
                        'percentage' => $percentage_new,
                        'typescrap_id' => $typescrapSelected->id,
                        'location_id' => $location,
                        'state' => $state,
                        'state_item' => 'scraped',
                    ]);

                }

                if ( $itemSelected->state_item == 'exited' )
                {
                    // TODO: Sumamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current + $percentage_new;
                    $materialSelected->save();

                    // TODO: Creamos la entrada y el item
                    $entry = Entry::create([
                        'entry_type' => "Retacería",
                        'date_entry' => Carbon::now(),
                        'finance' => false
                    ]);
                    $detail_entry = DetailEntry::create([
                        'entry_id' => $entry->id,
                        'material_id' => $material_id,
                    ]);

                    $itemNuevo = Item::create([
                        'detail_entry_id' => $detail_entry->id,
                        'material_id' => $materialSelected->id,
                        'code' => $new_code,
                        'length' => $length_new,
                        'width' => $width_new,
                        'weight' => 0,
                        'price' => $price_new,
                        'percentage' => $percentage_new,
                        'typescrap_id' => $typescrapSelected->id,
                        'location_id' => $location,
                        'state' => $state,
                        'state_item' => 'scraped',
                    ]);

                }
            }

            if ( $typescrap == 3 )
            {
                $materialSelected = Material::find($material_id);
                $itemSelected = Item::find($idItem);
                $typescrapSelected = Typescrap::find($typescrap);

                //TODO: Vamos a crear un item y luego modificamos el itemSelected
                //TODO: Nuevo Item
                $new_code = $this->generateRandomString(25);
                $areaOriginal = round($length, 2);
                $areaScrap = round($length_new, 2);
                $percentage_new = round($areaScrap/$typescrapSelected->length, 2);
                $price_new = round($price * $percentage_new, 2);

                $areaOld = round(($itemSelected->length - $length_new), 2);
                $percentage_old = round($areaOld/$typescrapSelected->length, 2);
                $price_old = round($price * $percentage_old, 2);

                $length_old = round($itemSelected->length - $length_new, 2);

                if ( $itemSelected->state_item != 'exited' )
                {
                    // TODO: Restamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current-$itemSelected->percentage;
                    $materialSelected->save();
                    // TODO: Sumamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current + $percentage_old + $percentage_new;
                    $materialSelected->save();
                    // TODO: Actualizamos el item anterior
                    $itemSelected->length = $length_old ;
                    $itemSelected->price = $price_old ;
                    $itemSelected->percentage = $percentage_old ;
                    $itemSelected->state_item = 'scraped';
                    $itemSelected->save();
                    // TODO: Creamos la entrada y el item
                    $entry = Entry::create([
                        'entry_type' => "Retacería",
                        'date_entry' => Carbon::now(),
                        'finance' => false
                    ]);
                    $detail_entry = DetailEntry::create([
                        'entry_id' => $entry->id,
                        'material_id' => $material_id,
                    ]);

                    $itemNuevo = Item::create([
                        'detail_entry_id' => $detail_entry->id,
                        'material_id' => $materialSelected->id,
                        'code' => $new_code,
                        'length' => $length_new,
                        'width' => 0,
                        'weight' => 0,
                        'price' => $price_new,
                        'percentage' => $percentage_new,
                        'typescrap_id' => $typescrapSelected->id,
                        'location_id' => $location,
                        'state' => $state,
                        'state_item' => 'scraped',
                    ]);

                }

                if ( $itemSelected->state_item == 'exited' )
                {
                    // TODO: Sumamos el stock
                    $materialSelected->stock_current = $materialSelected->stock_current + $percentage_new;
                    $materialSelected->save();

                    // TODO: Creamos la entrada y el item
                    $entry = Entry::create([
                        'entry_type' => "Retacería",
                        'date_entry' => Carbon::now(),
                        'finance' => false
                    ]);
                    $detail_entry = DetailEntry::create([
                        'entry_id' => $entry->id,
                        'material_id' => $material_id,
                    ]);

                    $itemNuevo = Item::create([
                        'detail_entry_id' => $detail_entry->id,
                        'material_id' => $materialSelected->id,
                        'code' => $new_code,
                        'length' => $length_new,
                        'width' => 0,
                        'weight' => 0,
                        'price' => $price_new,
                        'percentage' => $percentage_new,
                        'typescrap_id' => $typescrapSelected->id,
                        'location_id' => $location,
                        'state' => $state,
                        'state_item' => 'scraped',
                    ]);

                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Retazo guardado con éxito.'], 200);

    }

    public function generateRandomString($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getJsonDataMaterial( $material_id )
    {
        $material = Material::with('typeScrap')
            ->find($material_id);
        return json_encode($material);
    }

    public function storeNewScrap( Request $request )
    {
        //dd($request);
        $material_id = (int)$request->get('material_id_nuevo');
        $material = $request->get('material_nuevo');
        $price = (float)$request->get('price_nuevo');
        $typescrap = (int)$request->get('typescrap_nuevo');
        $code = $request->get('code_nuevo');
        $length = (float)$request->get('length_nuevo'); // Medida original
        $width = (float)$request->get('width_nuevo');// Medida original
        $length_new = ($request->get('length_new_nuevo') == null) ? 0:(float)$request->get('length_new_nuevo');
        $width_new = ($request->get('width_new_nuevo') == null) ? 0:(float)$request->get('width_new_nuevo');
        $location = ($request->get('location_nuevo') == null) ? 1:$request->get('location_nuevo');
        $state = $request->get('state_nuevo');

        // TODO: CHECKAR CUADNO SACAN UNA PARTE DE TODO EL LARGO O ANCHO PORQUE QUEDA CERO
        DB::beginTransaction();
        try {

            if ( $typescrap == 1 || $typescrap == 2 )
            {
                if ( $length_new == 0 || $width_new == 0 )
                {
                    return response()->json(['message' => 'Ingrese el largo o ancho mayor a cero'], 422);
                }
            }

            if ( $typescrap == 3 )
            {
                if ( $length_new == 0 )
                {
                    return response()->json(['message' => 'Ingrese el largo mayor a cero'], 422);
                }
            }

            if ( $typescrap == 1 || $typescrap == 2 )
            {
                $materialSelected = Material::find($material_id);
                $typescrapSelected = Typescrap::find($typescrap);

                //TODO: Vamos a crear un item y luego modificamos el itemSelected
                //TODO: Nuevo Item
                $new_code = $code;
                $areaComplete = round($typescrapSelected->length*$typescrapSelected->width,2);
                $areaScrap = round($length_new * $width_new, 2);
                $percentage_new = round($areaScrap/$areaComplete, 2);
                $price_new = round($price * $percentage_new, 2);

                // TODO: Sumamos el stock
                $materialSelected->stock_current = $materialSelected->stock_current + $percentage_new;
                $materialSelected->save();

                // TODO: Creamos la entrada y el item
                $entry = Entry::create([
                    'entry_type' => "Retacería",
                    'date_entry' => Carbon::now(),
                    'finance' => false
                ]);
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_id' => $material_id,
                ]);

                $itemNuevo = Item::create([
                    'detail_entry_id' => $detail_entry->id,
                    'material_id' => $materialSelected->id,
                    'code' => $new_code,
                    'length' => $length_new,
                    'width' => $width_new,
                    'weight' => 0,
                    'price' => $price_new,
                    'percentage' => $percentage_new,
                    'typescrap_id' => $typescrapSelected->id,
                    'location_id' => $location,
                    'state' => $state,
                    'state_item' => 'scraped',
                ]);

            }

            if ( $typescrap == 3 )
            {
                $materialSelected = Material::find($material_id);
                $typescrapSelected = Typescrap::find($typescrap);

                //TODO: Nuevo Item
                $new_code = $code;
                $areaScrap = round($length_new, 2);
                $percentage_new = round($areaScrap/$typescrapSelected->length, 2);
                $price_new = round($price * $percentage_new, 2);

                // TODO: Sumamos el stock
                $materialSelected->stock_current = $materialSelected->stock_current + $percentage_new;
                $materialSelected->save();

                // TODO: Creamos la entrada y el item
                $entry = Entry::create([
                    'entry_type' => "Retacería",
                    'date_entry' => Carbon::now(),
                    'finance' => false
                ]);
                $detail_entry = DetailEntry::create([
                    'entry_id' => $entry->id,
                    'material_id' => $material_id,
                ]);

                $itemNuevo = Item::create([
                    'detail_entry_id' => $detail_entry->id,
                    'material_id' => $materialSelected->id,
                    'code' => $new_code,
                    'length' => $length_new,
                    'width' => 0,
                    'weight' => 0,
                    'price' => $price_new,
                    'percentage' => $percentage_new,
                    'typescrap_id' => $typescrapSelected->id,
                    'location_id' => $location,
                    'state' => $state,
                    'state_item' => 'scraped',
                ]);

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Retazo guardado con éxito.'], 200);

    }
}
