<?php

namespace App\Http\Controllers;

use App\ContactName;
use App\Customer;
use App\Entry;
use App\Location;
use App\Material;
use App\Output;
use App\Supplier;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $customerCount = Customer::count();
        $contactNameCount = ContactName::count();
        $supplierCount = Supplier::count();
        $materialCount = Material::count();
        $entriesCount = Entry::where('finance', false)->count();
        $invoiceCount = Entry::where('finance', true)->count();
        $outputCount = Output::count();

        $locations = [];
        $locations2 = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->get();
        foreach ( $locations2 as $location )
        {
            $l = 'AR:'.$location->area->name.'|AL:'.$location->warehouse->name.'|AN:'.$location->shelf->name.'|NIV:'.$location->level->name.'|CON:'.$location->container->name.'|POS:'.$location->position->name;
            array_push($locations, ['id'=> $location->id, 'location' => $l]);
        }
        return view('dashboard.dashboard',
            compact('customerCount',
                'contactNameCount',
                'supplierCount',
                'materialCount',
                'entriesCount',
                'invoiceCount',
                'outputCount',
                'locations'));
    }
}
