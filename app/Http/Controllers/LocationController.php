<?php

namespace App\Http\Controllers;

use App\Item;
use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])
            ->get();

        return view('inventory.locations', compact('locations'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Location $location)
    {
        //
    }

    public function edit(Location $location)
    {
        //
    }

    public function update(Request $request, Location $location)
    {
        //
    }

    public function destroy(Location $location)
    {
        //
    }

    public function getLocations()
    {
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->get();

        //dd(datatables($materials)->toJson());
        return datatables($locations)->toJson();
    }

    public function getJsonLocations()
    {
        $array = [];
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->get();
        foreach ( $locations as $location )
        {
            $l = 'AR:'.$location->area->name.'|AL:'.$location->warehouse->name.'|AN:'.$location->shelf->name.'|NIV:'.$location->level->name.'|CON:'.$location->container->name.'|CON:'.$location->position->name;
            array_push($array, ['id'=> $location->id, 'location' => $l]);
        }
        //dd($array);
        return $array;
    }

    public function getItemsLocation($id)
    {
        $items = Item::where('location_id', $id)->whereIn('state_item', ['entered', 'scraped'])->with('material')->with('MaterialType')->with('DetailEntry')->get();

        //dd(datatables($items)->toJson());
        return datatables($items)->toJson();

    }

    public function getMaterialsByLocation($id)
    {
        $location = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->find($id);
        return view('inventory.items', compact('location'));
    }
}
