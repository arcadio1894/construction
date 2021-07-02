<?php

namespace App\Http\Controllers;

use App\Area;
use App\Container;
use App\Level;
use App\Position;
use App\Shelf;
use App\Transfer;
use App\Warehouse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $areas = Area::all();
        return view('transfer.create', compact('areas'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Transfer $transfer)
    {
        //
    }

    public function edit(Transfer $transfer)
    {
        //
    }

    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    public function destroy(Transfer $transfer)
    {
        //
    }

    public function getWarehouse($id)
    {
        $warehouses = Warehouse::where('area_id', $id)->get();
        $array = [];
        foreach ( $warehouses as $warehouse )
        {
            array_push($array, ['id'=> $warehouse->id, 'warehouse' => $warehouse->name]);
        }

        return $array;
    }

    public function getShelf($id)
    {
        $shelves = Shelf::where('warehouse_id', $id)->get();
        $array = [];
        foreach ( $shelves as $shelf )
        {
            array_push($array, ['id'=> $shelf->id, 'shelf' => $shelf->name]);
        }

        return $array;
    }

    public function getLevel($id)
    {
        $levels = Level::where('shelf_id', $id)->get();
        $array = [];
        foreach ( $levels as $level )
        {
            array_push($array, ['id'=> $level->id, 'level' => $level->name]);
        }

        return $array;
    }

    public function getContainer($id)
    {
        $containers = Container::where('level_id', $id)->get();
        $array = [];
        foreach ( $containers as $container )
        {
            array_push($array, ['id'=> $container->id, 'container' => $container->name]);
        }

        return $array;
    }

    public function getPosition($id)
    {
        $positions = Position::where('container_id', $id)->get();
        $array = [];
        foreach ( $positions as $position )
        {
            array_push($array, ['id'=> $position->id, 'position' => $position->name]);
        }

        return $array;
    }
}
