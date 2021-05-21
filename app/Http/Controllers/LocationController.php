<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container'])
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
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container'])->get();

        //dd(datatables($materials)->toJson());
        return datatables($locations)->toJson();
    }
}