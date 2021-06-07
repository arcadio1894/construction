<?php

namespace App\Http\Controllers;

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
        $items = Item::with(['location', 'materialType', 'material', 'detailEntry'])
            ->where('material_id', $id_material)
            ->get();
        foreach ( $items as $item )
        {
            $l = 'AR:'.$item->location->area->name.'|AL:'.$item->location->warehouse->name.'|AN:'.$item->location->shelf->name.'|NIV:'.$item->location->level->name.'|CON:'.$item->location->container->name;
            array_push($array,
                [
                    'id'=> $item->id,
                    'location' => $l,
                    'location_id' => $item->location->id,
                    'materialType' => $item->materialType,
                    'material' => $item->material->description,
                    'material_id' => $item->material->id,
                    'price' => $item->price,
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
}
