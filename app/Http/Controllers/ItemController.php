<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Item $item)
    {
        //
    }

    public function edit(Item $item)
    {
        //
    }

    public function update(Request $request, Item $item)
    {
        //
    }

    public function destroy(Item $item)
    {
        //
    }

    public function getJsonItems($id_material)
    {
        $array = [];
        $items = Item::with(['location', 'typescrap', 'material', 'detailEntry'])
            ->where('material_id', $id_material)->where('state_item','exited')
            ->get();
        foreach ( $items as $item )
        {
            $l = 'AR:'.$item->location->area->name.'|AL:'.$item->location->warehouse->name.'|AN:'.$item->location->shelf->name.'|NIV:'.$item->location->level->name.'|CON:'.$item->location->container->name;
            array_push($array,
                [
                    'id'=> $item->id,
                    'location' => $l,
                    'location_id' => $item->location->id,
                    'typescrap' => (isset($item->typescrap)) ? $item->typescrap->id : '',
                    'material' => $item->material->full_description,
                    'material_id' => $item->material->id,
                    'price' => $item->material->unit_price,
                    'state' => $item->state,
                    'code' => $item->code,
                    'length' => $item->length,
                    'width' => $item->width,
                    'weight' => $item->weight,
                    'detailEntry' => $item->detailEntry->id,
                ]);
        }
        //dd($array);
        return $array;
    }

    public function getJsonItemsEntry($entry)
    {
        $arrayItems = [];
        $arrayDetails = [];

        $entry = Entry::find($entry);
        foreach ($entry->details as $detail) {

            array_push($arrayDetails,
                [
                    'id'=> $detail->id,
                    'material' => $detail->material->full_description,
                    'code' => $detail->material->code,
                    'ordered_quantity' => $detail->ordered_quantity,
                    'unit_price' => $detail->unit_price
                ]);

            $items = Item::with(['location', 'typescrap', 'material', 'detailEntry'])
                ->where('detail_entry_id', $detail->id)
                ->get();
            foreach ( $items as $key => $item )
            {
                $l = 'AR:'.$item->location->area->name.'|AL:'.$item->location->warehouse->name.'|AN:'.$item->location->shelf->name.'|NIV:'.$item->location->level->name.'|CON:'.$item->location->container->name;
                array_push($arrayItems,
                    [
                        'id'=> $key+1,
                        'material' => $item->material->full_description,
                        'code' => $item->code,
                        'length' => $item->length,
                        'width' => $item->width,
                        'weight' => $item->weight,
                        'price' => $item->material->unit_price,
                        'location' => $l,
                        'state' => $item->state,
                    ]);
            }
        }

        //dd($array);
        return response()->json(['details' => $arrayDetails, 'items' => $arrayItems], 200);

    }

    public function getJsonItemsOutput($id_material)
    {
        $array = [];
        $items = Item::with(['location', 'typescrap', 'material', 'detailEntry'])
            ->where('material_id', $id_material)->whereIn('state_item',['entered', 'scraped'])
            ->get();
        foreach ( $items as $item )
        {
            $l = 'AR:'.$item->location->area->name.'|AL:'.$item->location->warehouse->name.'|AN:'.$item->location->shelf->name.'|NIV:'.$item->location->level->name.'|CON:'.$item->location->container->name;
            array_push($array,
                [
                    'id'=> $item->id,
                    'location' => $l,
                    'location_id' => $item->location->id,
                    'typescrap' => (isset($item->typescrap)) ? $item->typescrap->id : '',
                    'material' => $item->material->full_description,
                    'material_id' => $item->material->id,
                    'price' => $item->material->unit_price,
                    'state' => $item->state,
                    'code' => $item->code,
                    'length' => $item->length,
                    'width' => $item->width,
                    'weight' => $item->weight,
                    'detailEntry' => $item->detailEntry->id,
                    'percentage' => $item->percentage,
                ]);
        }
        //dd($array);
        return json_encode($array);
    }
}
